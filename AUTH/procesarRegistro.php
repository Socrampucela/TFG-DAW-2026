<?php
require_once '../config/db.php';
require_once '../classes/usuario.php';
require_once '../DAO/usuarioDAO.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    #Validar captcha:
    $captcha = $_POST['cf-turnstile-response'] ?? '';
    
    if (empty($captcha)) {
        header("Location: ../VIEWS/registro.php?error=captcha");
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
        header("Location: ../VIEWS/registro.php?error=captcha");
        exit();
    }
    
    #Procesar registro:
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);
    $usuario = new Usuario($nombre,$email,$pass_encriptada);
    
    try {
        $usuarioDAO = new usuarioDAO($conn);
        $resultado = $usuarioDAO->crear($usuario);

        if ($resultado) {
            header("Location: ../VIEWS/login.php?registro=exito");
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