<?php


try {
    $ip_servidor = '26.10.79.144'; 
    
    if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
        $host = 'localhost';
    } else {
        $host = $ip_servidor;
    }

    $user = 'root';
    $pass = '';
    $db   = 'proyecto_empleo_db';
    $port = 3306;

    $conn = new mysqli($host, $user, $pass, $db, $port);
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}