<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombreApellidos, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $pass_encrypted);
        
        $pass_encrypted = $pass_encriptada; 

        if ($stmt->execute()) {
            header("Location: ../login.php?registro=exito");
        }
        
        $stmt->close();
    } catch (Exception $e) {
        if ($conn->errno == 1062) {
            die("El correo ya está registrado.");
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}
$conn->close();