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
print($tipo_mensaje . " " . $mensaje);
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Iniciar sesión</title>

   <!-- CSS -->
   <link rel="stylesheet" href="../ASSETS/css/components.css">
  

   <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="page-center">

   <main class="w-full flex justify-center">
      <section class="panel" style="width:100%; max-width:520px;">
         <div class="panel__inner">

            <h1 class="page-title">Iniciar sesión</h1>
            <p class="page-subtitle">Accede a tu cuenta para postular a ofertas.</p>

            <form class="form" action="../AUTH/procesarLogin.php" method="post" id="formulario">

               <div>
                  <label class="form-label" for="email">Correo electrónico</label>
                  <input class="form-input" type="email" id="email" name="email" required>
               </div>

               <div>
                  <label class="form-label" for="password">Contraseña</label>
                  <input class="form-input" type="password" id="password" name="password" required>
               </div>

               <div class="captcha-wrap">
                  <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>
               </div>

               <button class="btn-primary" type="submit">Iniciar sesión</button>

               <div class="form-links">
                  ¿No tienes cuenta? <a href="register.php">Regístrate</a>
               </div>

            </form>

            <div id="errores"></div>

         </div>
      </section>
   </main>
<?php include '../INCLUDES/footer.php'; ?>

   <script src="../AUTH/comprobarRegistro.js"></script>
</body>
</html>
