<?php
require_once '../INCLUDES/funciones-comunes.php';
$datosAlerta = obtenerMensajeDeGet();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php renderizarHead('Iniciar sesión - Empleo360'); ?>
    <?php renderizarEstilosTailwind(); ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="
  min-h-screen flex flex-col
  bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]
">


    <?php include("../includes/header.php"); ?>

    <main class="flex-1 flex justify-center items-start py-10 px-4">

        <section class="panel">
            <div class="panel__inner">
                <h1 class="page-title">Iniciar sesión</h1>
                <p class="page-subtitle">Accede a tu cuenta para postular a ofertas.</p>

                <?php renderizarAlerta($datosAlerta['mensaje'], $datosAlerta['tipo']); ?>
                
                <div id="divErrores"></div>

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

                <script src="../AUTH/comprobarLogin.js"></script>
            </div>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>
</body>
</html>