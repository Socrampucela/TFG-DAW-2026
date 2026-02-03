<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION["logueado"]) || empty($_SESSION["usuario_id"])) {
  http_response_code(401);
  echo json_encode(["ok" => false, "error" => "No autorizado."]);
  exit;
}

require_once("../../config/db.php");
require_once("../../DAO/usuarioDAO.php");

function out($code, $arr) {
  http_response_code($code);
  echo json_encode($arr);
  exit;
}

function validEmail($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
  out(400, ["ok" => false, "error" => "JSON inválido."]);
}

$accion = $data["accion"] ?? "";
$usuarioId = (int)$_SESSION["usuario_id"];

try {
  $dao = new UsuarioDAO($conn);

  // Usuario actual (para email actual y comparación)
  $u = $dao->obtenerPorId($usuarioId);
  if (!$u) {
    out(404, ["ok" => false, "error" => "Usuario no encontrado."]);
  }
  $emailActual = (string)($u["email"] ?? "");

  // ---------------------------------------------------
  // START_UPDATE: guardar cambios pendientes + enviar mail
  // ---------------------------------------------------
  if ($accion === "start_update") {
    $nombre = trim((string)($data["nombre"] ?? ""));
    $emailNuevo = trim((string)($data["email"] ?? ""));
    $password = (string)($data["password"] ?? "");

    // Validaciones
    if ($nombre === "" || mb_strlen($nombre) < 2 || mb_strlen($nombre) > 80) {
      out(422, ["ok" => false, "error" => "El nombre debe tener entre 2 y 80 caracteres."]);
    }

    if ($emailNuevo !== "" && !validEmail($emailNuevo)) {
      out(422, ["ok" => false, "error" => "Email no válido."]);
    }

    // ✅ Contraseña: MISMA restricción que en comprobarRegistro.js (mínimo 6)
    if ($password !== "" && mb_strlen($password) < 6) {
      out(422, ["ok" => false, "error" => "La contraseña debe tener al menos 6 caracteres."]);
    }

    // Construir cambios pendientes SOLO si cambian
    $pending = [];

    if ($nombre !== (string)($u["nombre_apellido"] ?? "")) {
      $pending["nombre_apellido"] = $nombre;
    }

    if ($emailNuevo !== "" && $emailNuevo !== $emailActual) {
      $pending["email"] = $emailNuevo;
    }

    if ($password !== "") {
      // UsuarioDAO->actualizar ya se encarga de hashear si está implementado así
      $pending["password"] = $password;
    }

    if (empty($pending)) {
      out(200, ["ok" => false, "error" => "No hay cambios que guardar."]);
    }

    // Generar código (6 dígitos) y guardar en sesión (10 min)
    $code = random_int(100000, 999999);
    $_SESSION["perfil_pending"] = $pending;
    $_SESSION["perfil_code"] = (string)$code;
    $_SESSION["perfil_exp"] = time() + 600;

    // Enviar mail al email ACTUAL
    $subject = "Código de verificación para cambios de perfil";
    $message = "Hola,\n\nTu código de verificación es: $code\n\nCaduca en 10 minutos.\n\nSi no has solicitado estos cambios, ignora este correo.";
    $headers = "From: no-reply@tudominio.com\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = @mail($emailActual, $subject, $message, $headers);

    if (!$sent) {
      unset($_SESSION["perfil_pending"], $_SESSION["perfil_code"], $_SESSION["perfil_exp"]);
      out(500, ["ok" => false, "error" => "No se pudo enviar el correo de verificación."]);
    }

    out(200, ["ok" => true, "step" => "code", "msg" => "Código enviado al email actual."]);
  }

  // ---------------------------------------------------
  // CONFIRM_UPDATE: validar código + aplicar cambios
  // ---------------------------------------------------
  if ($accion === "confirm_update") {
    $code = trim((string)($data["code"] ?? ""));

    if (!preg_match('/^\d{6}$/', $code)) {
      out(422, ["ok" => false, "error" => "El código debe tener 6 dígitos."]);
    }

    $sessCode = $_SESSION["perfil_code"] ?? null;
    $exp = (int)($_SESSION["perfil_exp"] ?? 0);
    $pending = $_SESSION["perfil_pending"] ?? null;

    if (!$sessCode || !$pending || !is_array($pending)) {
      out(400, ["ok" => false, "error" => "No hay cambios pendientes de confirmar."]);
    }

    if (time() > $exp) {
      unset($_SESSION["perfil_pending"], $_SESSION["perfil_code"], $_SESSION["perfil_exp"]);
      out(400, ["ok" => false, "error" => "El código ha caducado."]);
    }

    if (!hash_equals((string)$sessCode, (string)$code)) {
      out(400, ["ok" => false, "error" => "Código incorrecto."]);
    }

    $ok = $dao->actualizar($usuarioId, $pending);

    unset($_SESSION["perfil_pending"], $_SESSION["perfil_code"], $_SESSION["perfil_exp"]);

    if (!$ok) {
      out(500, ["ok" => false, "error" => "No se pudieron aplicar los cambios."]);
    }

    // Leer usuario actualizado para devolver UI
    $updated = $dao->obtenerPorId($usuarioId);

    out(200, [
      "ok" => true,
      "msg" => "Cambios aplicados correctamente.",
      "nombre" => $updated["nombre_apellido"] ?? null,
      "email"  => $updated["email"] ?? null
    ]);
  }

  // ---------------------------------------------------
  // DELETE: borrado directo (sin mail)
  // ---------------------------------------------------
  if ($accion === "delete") {
    $ok = $dao->eliminar($usuarioId);

    if (!$ok) {
      out(500, ["ok" => false, "error" => "No se pudo eliminar la cuenta."]);
    }

    session_destroy();
    out(200, ["ok" => true, "msg" => "Cuenta eliminada correctamente."]);
  }

  out(400, ["ok" => false, "error" => "Acción no válida."]);

} catch (Throwable $e) {
  out(500, ["ok" => false, "error" => "Error interno."]);
}

