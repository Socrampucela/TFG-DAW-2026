<!DOCTYPE html>
<html lang="es">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style type="text/tailwindcss">
        @layer components {
            .register-container {
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
        }
    </style>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <?php include("../includes/header.php"); ?>

    <main class="flex justify-center items-start py-10 px-4">
        <section class="register-container">
            <h1 class="page-title">Regístrate</h1>
            <p class="page-subtitle">Crea tu perfil y empieza a postular a ofertas.</p>

            <form action="../AUTH/procesarRegistro.php" method="post" id="formulario">
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

                <div class="flex items-center gap-2 mb-6 text-sm text-gray-600">
                    <input type="checkbox" id="condiciones" name="condiciones" required class="w-4 h-4 rounded text-blue-600">
                    <label for="condiciones">Acepto los términos y condiciones</label>
                </div>

                <div class="mb-6 flex justify-center">
                    <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>
                </div>

                <button class="btn-primary" type="submit">Registrarse</button>

                <div class="mt-6 text-center text-sm text-gray-600">
                    ¿Ya tienes cuenta? <a href="login.php" class="text-blue-600 font-bold hover:underline">Inicia sesión</a>
                </div>
            </form>
            
            <div id="errores"></div>
            <script src="../AUTH/comprobarRegistro.js"></script>
        </section>
    </main>

</body>
</html>