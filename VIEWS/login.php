<?php
$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['registro'])) {
    switch ($_GET['registro']) {
        case 'exito':
            $mensaje = 'Cuenta creada exitosamente. Ya puedes iniciar sesión.';
            $tipo_mensaje = 'Exito';
            break;
        case 'error':
            $mensaje = 'Error al crear la cuenta. Inténtalo de nuevo.';
            $tipo_mensaje = 'Error';
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'credenciales':
            $mensaje = 'Correo electrónico o contraseña incorrectos.';
            $tipo_mensaje = 'Error';
            break;
        case 'sesion':
            $mensaje = 'Debes iniciar sesión para acceder a esta página.';
            $tipo_mensaje = 'Advertencia';
            break;
    }
}
print($tipo_mensaje. " " . $mensaje)
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Iniciar sesión</title>
   <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>
   <form action="../AUTH/procesarLogin.php" method="post">
                <div>
                    <label for="email">Correo electrónico</label><br>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="password">Contraseña</label><br>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>

                <button type="submit">Iniciar sesión</button>
            </form>
</body>
</html>