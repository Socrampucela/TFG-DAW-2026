<!DOCTYPE html>
<html lang="es">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <link rel="stylesheet" href="../ASSETS/css/register.css">

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <?php include("../includes/header.php"); ?>

    <main class="flex-grow flex justify-center items-start py-10 px-4">
        <!-- Unificado con login: usamos panel -->
        <section class="panel" style="width:100%; max-width:520px;">
            <div class="panel__inner">

                <h1 class="page-title">Regístrate</h1>
                <p class="page-subtitle">Crea tu perfil y empieza a postular a ofertas.</p>

                <form class="form" action="../AUTH/procesarRegistro.php" method="post" id="formulario">

                    <div>
                        <label class="form-label" for="nombre">Nombre y apellidos</label>
                        <input class="form-input" type="text" id="nombre" name="nombre" required>
                    </div>

                    <div>
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input class="form-input" type="email" id="email" name="email" required>
                    </div>

                    <div>
                        <label class="form-label" for="password">Contraseña</label>
                        <input class="form-input" type="password" id="password" name="password" required>
                    </div>

                    <div>
                        <label class="form-label" for="password2">Repetir contraseña</label>
                        <input class="form-input" type="password" id="password2" name="password2" required>
                    </div>

                    <!-- Unificado: checkbox-row del components -->
                    <div class="checkbox-row">
                        <input type="checkbox" id="condiciones" name="condiciones" required>
                        <label for="condiciones">Acepto los términos y condiciones</label>
                    </div>

                    <!-- Unificado: captcha-wrap del components -->
                    <div class="captcha-wrap">
                        <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>
                    </div>

                    <button class="btn-primary" type="submit">Registrarse</button>

                    <!-- Unificado: form-links del components -->
                    <div class="form-links">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </div>

                </form>

                <div id="errores"></div>
                <script src="../AUTH/comprobarRegistro.js"></script>

            </div>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
