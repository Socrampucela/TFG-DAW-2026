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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style type="text/tailwindcss">
        @layer components {
            .login-container {
                @apply bg-white border border-gray-200 rounded-2xl shadow-2xl p-8 max-w-[520px] w-full mt-10;
            }
            .form-label {
                @apply block text-sm font-semibold text-gray-700 mb-1;
            }
            .form-input {
                @apply w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all mb-4;
            }
            .btn-primary {
                @apply w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg mt-4;
            }
            .page-title {
                @apply text-3xl font-bold tracking-tight text-gray-900 mb-2;
            }
            .page-subtitle {
                @apply text-sm text-gray-500 mb-6;
            }
            /* Estilos para las alertas */
            .alert {
                @apply p-4 mb-6 rounded-lg text-sm font-medium border;
            }
            .alert-Exito { @apply bg-green-50 text-green-700 border-green-200; }
            .alert-Error { @apply bg-red-50 text-red-700 border-red-200; }
            .alert-Advertencia { @apply bg-yellow-50 text-yellow-700 border-yellow-200; }
        }
    </style>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <?php include("../includes/header.php"); ?>

    <main class="flex justify-center items-start py-10 px-4">
        <section class="login-container">
            
            <h1 class="page-title">Iniciar sesión</h1>
            <p class="page-subtitle">Accede a tu cuenta para postular a ofertas.</p>

            <?php if ($mensaje): ?>
                <div id="divError" class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <strong><?php echo $tipo_mensaje; ?>:</strong> <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

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


         </div>
      </section>
   </main>
<?php include '../INCLUDES/footer.php'; ?>

   <script src="../AUTH/comprobarRegistro.js"></script>
</body>
</html>
