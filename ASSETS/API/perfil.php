<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once '../../config/db.php';
require_once '../../classes/usuario.php';
require_once '../../DAO/usuarioDAO.php';

function out($ok, $message = "", $data = []) {
  echo json_encode(["ok" => $ok, "message" => $message, "data" => $data], JSON_UNESCAPED_UNICODE);
  exit();
}

if (empty($_SESSION["logueado"]) || empty($_SESSION["usuario_id"]) || empty($_SESSION["email"])) {
  out(false, "No autorizado.");
}

$action = $_POST["action"] ?? "";
$userId = (int)$_SESSION["usuario_id"];

$usuarioDAO = new UsuarioDAO($conn);

// Helpers códigos (session)
function makeCode(): string {
  return str_pad((string)random_int(0, 999999), 6, "0", STR_PAD_LEFT);
}
function setVerifySession(string $type, array $extra = []): string {
  $code = makeCode();
  $_SESSION["verify"] = [
    "type" => $type,
    "hash" => password_hash($code, PASSWORD_DEFAULT),
    "exp"  => time() + 600, // 10 min
    "extra"=> $extra
  ];
  return $code;
}
function verifyCode(string $code, string $type): array {
  $v = $_SESSION["verify"] ?? null;
  if (!$v || ($v["type"] ?? "") !== $type) return [false, "No hay verificación pendiente."];
  if (($v["exp"] ?? 0) < time()) return [false, "El código ha caducado."];
  if (!password_verify($code, $v["hash"] ?? "")) return [false, "Código incorrecto."];
  return [true, $v];
}
function sendMailCode(string $to, string $subject, string $body): bool {
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  $headers .= "From: no-reply@tu-dominio.com\r\n";
  return @mail($to, $subject, $body, $headers);
}

if ($action === "change_name") {
  $nombre = trim($_POST["nombre_apellido"] ?? "");
  if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) out(false, "Nombre inválido.");

  $ok = $usuarioDAO->actualizar($userId, ["nombre_apellido" => $nombre]);
  if (!$ok) out(false, "No se pudo actualizar el nombre.");

  $_SESSION["nombre"] = $nombre;
  out(true, "Nombre actualizado correctamente.");
}

if ($action === "request_email_change") {
  $newEmail = trim($_POST["new_email"] ?? "");
  if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) out(false, "Email nuevo inválido.");

  $currentEmail = (string)$_SESSION["email"];
  if (strcasecmp($newEmail, $currentEmail) === 0) out(false, "El nuevo email no puede ser igual al actual.");

  if ($usuarioDAO->emailExiste($newEmail, $userId)) out(false, "Ese email ya está registrado.");

  $code = setVerifySession("email_change", ["new_email" => $newEmail]);

  $to = $currentEmail; // email original
  $subject = "Código de verificación - cambio de email";
  $body = "Tu código de verificación es: $code\n\nCaduca en 10 minutos.\nSi no has solicitado este cambio, ignora este mensaje.";

  $sent = sendMailCode($to, $subject, $body);
  if (!$sent) {
    // En XAMPP esto es bastante común
    out(false, "No se pudo enviar el email (mail() no está configurado en este entorno).");
  }

  out(true, "Código enviado al email actual.");
}

if ($action === "confirm_email_change") {
  $code = trim($_POST["code"] ?? "");
  if (!preg_match('/^\d{6}$/', $code)) out(false, "Código inválido.");

  [$ok, $v] = verifyCode($code, "email_change");
  if (!$ok) out(false, $v);

  $newEmail = $v["extra"]["new_email"] ?? "";
  if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) out(false, "Email nuevo inválido.");

  if ($usuarioDAO->emailExiste($newEmail, $userId)) out(false, "Ese email ya está registrado.");

  $upd = $usuarioDAO->actualizar($userId, ["email" => $newEmail]);
  if (!$upd) out(false, "No se pudo actualizar el email.");

  $_SESSION["email"] = $newEmail;
  unset($_SESSION["verify"]);

  out(true, "Email actualizado correctamente.");
}

if ($action === "request_password_change") {
  $currentPass = $_POST["current_password"] ?? "";
  $newPass = $_POST["new_password"] ?? "";

  if (!$currentPass || !$newPass) out(false, "Faltan datos.");
  if (strlen($newPass) < 8) out(false, "La nueva contraseña debe tener al menos 8 caracteres.");

  // Verificar contraseña actual contra el hash guardado en BBDD
  $usuario = $usuarioDAO->buscarPorEmail((string)$_SESSION["email"]);
  if ($usuario === null) out(false, "Usuario no encontrado.");
  if (!password_verify($currentPass, $usuario->getPassword())) out(false, "Contraseña actual incorrecta.");

  // Guardamos HASH en sesión (no la contraseña en claro)
  $newHash = password_hash($newPass, PASSWORD_DEFAULT);

  $code = setVerifySession("password_change", ["new_hash" => $newHash]);

  $to = (string)$_SESSION["email"]; // email original
  $subject = "Código de verificación - cambio de contraseña";
  $body = "Tu código de verificación es: $code\n\nCaduca en 10 minutos.\nSi no has solicitado este cambio, ignora este mensaje.";

  $sent = sendMailCode($to, $subject, $body);
  if (!$sent) out(false, "No se pudo enviar el email (mail() no está configurado en este entorno).");

  out(true, "Código enviado al email actual.");
}

if ($action === "confirm_password_change") {
  $code = trim($_POST["code"] ?? "");
  if (!preg_match('/^\d{6}$/', $code)) out(false, "Código inválido.");

  [$ok, $v] = verifyCode($code, "password_change");
  if (!$ok) out(false, $v);

  $newHash = $v["extra"]["new_hash"] ?? "";
  if (!$newHash) out(false, "No hay contraseña pendiente.");

  // Actualizar directamente el hash (para no re-hashear)
  try {
    $stmt = $conn->prepare("UPDATE usuarios SET password = :p WHERE id = :id");
    $stmt->execute([":p" => $newHash, ":id" => $userId]);
  } catch (Throwable $e) {
    out(false, "No se pudo actualizar la contraseña.");
  }

  unset($_SESSION["verify"]);
  out(true, "Contraseña actualizada correctamente.");
}

if ($action === "delete_account") {
  $pass = $_POST["password"] ?? "";
  if (!$pass) out(false, "Falta la contraseña.");

  $usuario = $usuarioDAO->buscarPorEmail((string)$_SESSION["email"]);
  if ($usuario === null) out(false, "Usuario no encontrado.");
  if (!password_verify($pass, $usuario->getPassword())) out(false, "Contraseña incorrecta.");

  $ok = $usuarioDAO->eliminar($userId);
  if (!$ok) out(false, "No se pudo eliminar la cuenta.");

  session_destroy();
  out(true, "Cuenta eliminada.");
}

out(false, "Acción no válida.");


