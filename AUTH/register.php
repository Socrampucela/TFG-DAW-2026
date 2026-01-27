<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="ASSETS/css/register.css">
</head>
<body>
    <h1>Regístrate</h1>
    <form action="procesarRegistro.php" method="post">
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

        <button type="submit">Registrarse</button>
    </form>
</body>
</html>