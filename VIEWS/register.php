<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <!-- Tailwind CDN (solo utilidades, sin estilos propios) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <link rel="stylesheet" href="../ASSETS/css/register.css">


    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body class="min-h-screen flex items-center justify-center">
    
    <main class="w-full flex justify-center">
        <section class="register-container">
            <h1>Regístrate</h1>
            <p class="register-subtitle">Crea tu perfil y empieza a postular a ofertas.</p>

            <form action="../AUTH/procesarRegistro.php" method="post" id="formulario">
                <div>
                    <label for="nombre">Nombre y apellidos</label><br>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div>
                    <label for="email">Correo electrónico</label><br>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="password">Contraseña</label><br>
                    <input type="password" id="password" name="password" required>
                </div>

                <div>
                    <label for="password2">Repetir contraseña</label><br>
                    <input type="password" id="password2" name="password2" required>
                </div>

                <div>
                    <label>
                        <input type="checkbox" name="condiciones" required>
                        Acepto los términos y condiciones
                    </label>
                </div>

                <div class="cf-turnstile" data-sitekey="0x4AAAAAACT39mb_TupAZlv2"></div>

                <button type="submit">Registrarse</button>
                <div class="register-links">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                </div>

            </form>
            <div id="errores"></div>
            <script src="../AUTH/comprobarRegistro.js"></script>
        </section>
    </main>

</body>
</html>
