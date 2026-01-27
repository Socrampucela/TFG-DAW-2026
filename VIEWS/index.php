<?php
require_once '../CONFIG/db.php';
require_once '../CLASSES/usuario.php';
require_once '../DAO/usuarioDAO.php';

echo "<h2>Prueba de Sistema</h2>";

try {
    // 1. Probar Conexión
    if ($conn) {
        echo "✅ Conexión PDO establecida con éxito.<br>";
    }

    // 2. Probar DAO
    $usuarioDAO = new UsuarioDAO($conn);
    $emailPrueba = 'usuarioPrueba@gmail.com';
    
    $resultado = $usuarioDAO->buscarPorEmail($emailPrueba);

    if ($resultado) {
        echo "✅ Usuario encontrado: " . $resultado->getNombreApellidos();
    } else {
        echo "ℹ️ El email $emailPrueba no existe en la base de datos (esto no es un error).";
    }

} catch (Exception $e) {
    echo "❌ Error en la prueba: " . $e->getMessage();
}