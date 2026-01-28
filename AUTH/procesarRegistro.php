<?php
require_once '../config/db.php';
require_once '../classes/usuario.php';
require_once '../DAO/usuarioDAO.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);
    $usuario = new Usuario($nombre,$email,$pass_encriptada);
    try {
        $usuarioDAO = new usuarioDAO($conn);
        $resultado = $usuarioDAO->crear($usuario);

        if ($resultado) {
            header("Location: login.php?registro=exito");
            exit(); 
        }
        
    } catch (PDOException $e) {
        
        if ($e->getCode() == 23000) { 
            die("Error: El correo electrónico ya está registrado.");
        } else {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }
}