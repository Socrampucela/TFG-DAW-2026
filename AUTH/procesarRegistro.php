<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Encriptamos la contraseña
    $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);

    try {
        // Con PDO, usamos nombre de parámetros con ":" o simplemente "?"
        $sql = "INSERT INTO usuarios (nombre_apellido, email, password) VALUES (:nombre, :email, :pass)";
        $stmt = $conn->prepare($sql);
        
        // En PDO, pasamos los datos directamente en el execute como un array
        $resultado = $stmt->execute([
            ':nombre' => $nombre,
            ':email'  => $email,
            ':pass'   => $pass_encriptada
        ]);

        if ($resultado) {
            header("Location: login.php?registro=exito");
            exit(); // Siempre pon exit() después de un header Location
        }
        
    } catch (PDOException $e) {
        // En PDO, los errores de duplicado se manejan con el código de error SQLSTATE
        if ($e->getCode() == 23000) { 
            die("Error: El correo electrónico ya está registrado.");
        } else {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }
}