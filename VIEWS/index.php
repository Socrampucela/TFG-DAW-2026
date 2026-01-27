<?php
echo "IP del servidor: " . $_SERVER['SERVER_ADDR'] . "<br>";
echo "IP del cliente: " . $_SERVER['REMOTE_ADDR'] . "<br>";

try {
    $conn = new PDO("mysql:host=localhost;charset=utf8mb4", 'root', '');
    echo "Conexión a MySQL: OK<br>";
    
    $dbs = $conn->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Bases de datos: " . implode(', ', $dbs);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}