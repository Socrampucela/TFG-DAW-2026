<?php
require_once '../INCLUDES/funciones-comunes.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php renderizarHead('Registro - Empleo360'); ?>
    <?php renderizarEstilosTailwind(); ?>
    <link rel="stylesheet" href="../ASSETS/css/register.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style type="text/tailwindcss">
        @layer components {
            .checkbox-row { 
                @apply flex items-center gap-3 py-2; 
            }
            .checkbox-row input { 
                @apply w-5 h-5 cursor-pointer; 
            }
            .checkbox-row label { 
                @apply text-sm text-gray-600 cursor-pointer select-none; 
            }
        }

        @media (max-width: 640px) {
            main { @apply py-6 px-4 !important; }
            .panel__inner { @apply p-6 !important; }
            .page-title { @apply text-2xl mb-1 !important; }
            .page-subtitle { @apply text-sm mb-6 !important; }
            .form-input { @apply py-3 px-4 text-base !important; }
            .captcha-wrap { 
                @apply flex justify-center mb-4 overflow-hidden;
                transform: scale(0.85);
                transform-origin: center;
            }
            .btn-primary { @apply w-full py-4 text-lg !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <?php include("../includes/header.php"); ?>

    <main class="flex-grow flex justify-center items-start py-10 px-4">
        <section class="panel" style="width:100%; max-width:520px;">
            <div class="panel__inner">
                <h1 class="page-title">Regístrate</h1>
                <p class="page-subtitle">Crea tu perfil y empieza a postular a ofertas.</p>
                
                <div id="divErrores"></div>

                <form class="form" action="../AUTH/procesarRegistro.php" method="post" id="formulario" novalidate>
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
                        <input class="form-input" type="password" id="password" name="password" autocomplete="new-password" required>
                    </div>

                    <div>
                        <label class="form-label" for="password2">Repetir contraseña</label>
                        <input class="form-input" type="password" id="password2" name="password2" required>
                    </div>

                    <div class="checkbox-row">
                        <input type="checkbox" id="condiciones" name="condiciones" required>
                        <label for="condiciones">Acepto los <a href="terminos.php" target="_blank" class="text-blue-600 underline">términos y condiciones</a></label>
                    </div>

                    <div class="captcha-wrap">
                        <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>
                    </div>

                    <button class="btn-primary" type="submit">Registrarse</button>

                    <div class="form-links">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </div>
                </form>

                <script src="../AUTH/comprobarRegistro.js"></script>
            </div>
        </section>
    </main>

    <?php include("../includes/footer.php"); ?>
</body>
</html>