<?php
session_start();

require_once '../config/db.php';
require_once '../classes/usuario.php';
require_once '../DAO/usuarioDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    #Validar captcha:
    $captcha = $_POST['cf-turnstile-response'] ?? '';
    
    if (empty($captcha)) {
        header("Location: ../VIEWS/login.php?error=captcha");
        exit();
    }
    
    $secret = '0x4AAAAAACT39jJWImnHjcB6am2kzMlo-qw'; 
    
    $data = [
        'secret' => $secret,
        'response' => $captcha
    ];
    
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $verify = file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
    $captcha_success = json_decode($verify);
    
    if ($captcha_success->success == false) {
        header("Location: ../VIEWS/login.php?error=captcha");
        exit();
    }

    #ComprobarLogin:
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $usuarioDAO = new UsuarioDAO($conn);
        $usuario = $usuarioDAO->buscarPorEmail($email);

        if ($usuario === null) {
            header("Location: ../VIEWS/login.php?error=credenciales");
            exit();
        }

        if(password_verify($password, $usuario->getPassword())){
            $_SESSION["usuario_id"] = $usuario->getID();
            $_SESSION["email"] = $usuario->getEmail();
            $_SESSION["nombre"] = $usuario->getNombreApellidos();
            $_SESSION["rol"] = $usuario->getRol(); // 👈 NUEVO: Guardar el rol
            $_SESSION["logueado"] = true;

            // Redirigir según el rol
            if ($usuario->esAdmin()) {
                header("Location: ../VIEWS/admin/dashboard.php");
            } else {
                header("Location: ../VIEWS/index.php");
            }
            exit();
        }else{
            header("Location: ../VIEWS/login.php?error=credenciales");
            exit();
        }

    } catch (\Throwable $th) {
        die("Error en la base de datos: " . $th->getMessage());
    }
} else {
    header("Location: ../VIEWS/login.php");
    exit();
}