<?php
try {
    $host = '127.0.0.1'; // Usar IP en lugar de 'localhost'
    $db   = 'proyecto_empleo_db';
    $user = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
 
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}