<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=utf-8');

$root = dirname(__DIR__, 2); // .../TFG-DAW-2026/TFG-DAW-2026

require_once $root . "/CONFIG/db.php";
require_once $root . "/CLASSES/usuario.php";
require_once $root . "/DAO/usuarioDAO.php";

function json_fail(string $msg, int $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function json_ok(string $msg, array $extra = []) {
    echo json_encode(array_merge(['ok' => true, 'message' => $msg], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION["logueado"]) || $_SESSION["logueado"] !== true) {
    json_fail("No autorizado.", 401);
}

$usuarioId = (int)($_SESSION["usuario_id"] ?? 0);
$emailActual = (string)($_SESSION["email"] ?? "");

if ($usuarioId <= 0 || $emailActual === "") {
    json_fail("Sesión inválida.", 401);
}

$usuarioDAO = new UsuarioDAO($conn);

$action = $_POST['action'] ?? '';

/**
 * 1) Cambiar nombre (directo, sin código)
 */
if ($action === "update_name") {
    $nombre = trim($_POST['nombre_apellido'] ?? '');

    if ($nombre === '' || mb_strlen($nombre) < 3 || mb_strlen($nombre) > 100) {
        json_fail("El nombre debe tener entre 3 y 100 caracteres.");
    }

    $ok = $usuarioDAO->actualizar($usuarioId, [
        'nombre_apellido' => $nombre
    ]);

    if (!$ok) json_fail("No se pudo actualizar el nombre.");

    $_SESSION["nombre"] = $nombre;
    json_ok("Nombre actualizado correctamente.");
}

/**
 * 2) Enviar código (para email / contraseña)
 *    Guarda operación pendiente en $_SESSION["perfil_pending"] (sin tocar BD)
 */
if ($action === "send_code") {
    $type = $_POST['type'] ?? ''; // email | password

    if (!in_array($type, ['email', 'password'], true)) {
        json_fail("Tipo de cambio no válido.");
    }

    if ($type === 'email') {
        $newEmail = trim($_POST['new_email'] ?? '');
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            json_fail("Email nuevo no válido.");
        }
        if (strcasecmp($newEmail, $emailActual) === 0) {
            json_fail("El nuevo email es igual al actual.");
        }
        if ($usuarioDAO->emailExiste($newEmail, $usuarioId)) {
            json_fail("Ese email ya está en uso.");
        }
        $newValue = $newEmail;
    } else {
        $newPass = (string)($_POST['new_password'] ?? '');
        if (strlen($newPass) < 6 || strlen($newPass) > 72) {
            json_fail("La contraseña debe tener entre 6 y 72 caracteres.");
        }
        $newValue = $newPass;
    }

    $code = (string)random_int(100000, 999999);

    $_SESSION["perfil_pending"] = [
        'type' => $type,
        'value' => $newValue,
        'code_hash' => password_hash($code, PASSWORD_DEFAULT),
        'expires' => time() + 600, // 10 min
        'attempts' => 0,
        'to' => $emailActual
    ];

    // Envío con mail() al EMAIL ACTUAL
    $to = $emailActual;
    $subject = "Código de verificación (TFG-DAW-2026)";
    $message =
        "Has solicitado cambiar tu " . ($type === 'email' ? "email" : "contraseña") . ".\n\n" .
        "Tu código de verificación es: " . $code . "\n" .
        "Este código caduca en 10 minutos.\n\n" .
        "Si no has sido tú, ignora este correo.";

    $headers = [];
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/plain; charset=UTF-8";
    $headers[] = "From: TFG-DAW-2026 <no-reply@tfg-daw.local>";

    $sent = @mail($to, $subject, $message, implode("\r\n", $headers));

    if (!$sent) {
        // En local puede fallar. Dejo el pending igualmente.
        json_ok("Código generado. (mail() puede no enviar en local si no hay SMTP configurado.)", [
            'mail_sent' => false
        ]);
    }

    json_ok("Código enviado al email actual.", ['mail_sent' => true]);
}

/**
 * 3) Verificar código y aplicar el cambio en BD
 */
if ($action === "verify_and_apply") {
    $code = trim($_POST['code'] ?? '');

    if ($code === '' || !preg_match('/^\d{6}$/', $code)) {
        json_fail("El código debe tener 6 dígitos.");
    }

    $pending = $_SESSION["perfil_pending"] ?? null;
    if (!$pending) {
        json_fail("No hay ninguna operación pendiente. Vuelve a solicitar el código.");
    }

    if (($pending['expires'] ?? 0) < time()) {
        unset($_SESSION["perfil_pending"]);
        json_fail("El código ha caducado. Solicita uno nuevo.");
    }

    $attempts = (int)($pending['attempts'] ?? 0);
    if ($attempts >= 5) {
        unset($_SESSION["perfil_pending"]);
        json_fail("Demasiados intentos. Solicita un nuevo código.");
    }

    $_SESSION["perfil_pending"]['attempts'] = $attempts + 1;

    if (!password_verify($code, $pending['code_hash'])) {
        json_fail("Código incorrecto.");
    }

    $type = $pending['type'];
    $value = $pending['value'];

    if ($type === 'email') {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            unset($_SESSION["perfil_pending"]);
            json_fail("Email pendiente inválido.");
        }
        if ($usuarioDAO->emailExiste($value, $usuarioId)) {
            unset($_SESSION["perfil_pending"]);
            json_fail("Ese email ya está en uso.");
        }

        $ok = $usuarioDAO->actualizar($usuarioId, [
            'email' => $value
        ]);

        if (!$ok) json_fail("No se pudo actualizar el email.");

        $_SESSION["email"] = $value;
        unset($_SESSION["perfil_pending"]);
        json_ok("Email actualizado correctamente.");
    }

    if ($type === 'password') {
        // OJO: tu UsuarioDAO->actualizar() hashea aquí dentro (password_hash)
        $ok = $usuarioDAO->actualizar($usuarioId, [
            'password' => $value
        ]);

        if (!$ok) json_fail("No se pudo actualizar la contraseña.");

        unset($_SESSION["perfil_pending"]);
        json_ok("Contraseña actualizada correctamente.");
    }

    json_fail("Operación no válida.");
}

json_fail("Acción no válida.");
