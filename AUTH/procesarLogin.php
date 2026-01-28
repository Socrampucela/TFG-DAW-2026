<?php
session_start();

require_once '../config/db.php';
require_once '../classes/usuario.php';
require_once '../DAO/usuarioDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $usuarioDAO = new UsuarioDAO($conn);
        $usuario = $usuarioDAO->buscarPorEmail($email);

        if ($usuario === null) {
            header("Location: login.php?error=credenciales");
            exit();
        }

        if(password_verify($password, $usuario->getPassword())){
            $_SESSION["usuario_id"] = $usuario->getID();
            $_SESSION["email"] = $usuario->getEmail();
            $_SESSION["nombre"] = $usuario->getNombreApellidos();
            $_SESSION["logueado"] = true;

            header("Location: ../VIEWS/index.php");
            exit();
        }else{
            header("Location: login.php?error=credenciales");
            exit();
        }

    } catch (\Throwable $th) {
        die("Error en la base de datos: " . $e->getMessage());
    }
    }else {
    header("Location: login.php");
    exit();
    }