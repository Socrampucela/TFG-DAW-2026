<?php
// test.php
echo "<h2>Diagnóstico de conexión</h2>";
echo "IP del servidor PHP: " . $_SERVER['SERVER_ADDR'] . "<br>";
echo "IP del cliente: " . $_SERVER['REMOTE_ADDR'] . "<br>";
echo "Nombre del servidor: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "<hr>";

// Prueba 1: Conexión sin base de datos
try {
    $conn = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8mb4", 'root', '');
    echo "✓ Conexión a MySQL (sin BD): <strong style='color:green'>OK</strong><br>";
    
    // Listar bases de datos
    $dbs = $conn->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Bases de datos disponibles: <strong>" . implode(', ', $dbs) . "</strong><br>";
    
    // Verificar si existe proyecto_empleo_db
    if (in_array('proyecto_empleo_db', $dbs)) {
        echo "✓ Base de datos 'proyecto_empleo_db': <strong style='color:green'>ENCONTRADA</strong><br>";
    } else {
        echo "✗ Base de datos 'proyecto_empleo_db': <strong style='color:red'>NO ENCONTRADA</strong><br>";
    }
    
} catch (PDOException $e) {
    echo "✗ Error de conexión a MySQL: <strong style='color:red'>" . $e->getMessage() . "</strong><br>";
}

echo "<hr>";

// Prueba 2: Conexión directa a proyecto_empleo_db
try {
    $conn2 = new PDO("mysql:host=127.0.0.1;port=3306;dbname=proyecto_empleo_db;charset=utf8mb4", 'root', '');
    echo "✓ Conexión a 'proyecto_empleo_db': <strong style='color:green'>OK</strong><br>";
    
    // Listar tablas
    $tables = $conn2->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "✓ Tablas en la BD: <strong>" . implode(', ', $tables) . "</strong><br>";
    
} catch (PDOException $e) {
    echo "✗ Error conectando a 'proyecto_empleo_db': <strong style='color:red'>" . $e->getMessage() . "</strong><br>";
}

echo "<hr>";
echo "<h3>Información de rutas</h3>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";