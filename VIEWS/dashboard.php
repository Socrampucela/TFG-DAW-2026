<?php
include_once '../CLASSES/usuario.php';
include_once '../includes/header.php';
include_once "../DAO/empleoDAO.php";
include_once "../config/db.php";

$empleoDAO = new empleoDAO($conn);

if(esAdmin()){
    // Obtenemos los datos antes de empezar el HTML
    $estadisticas = $empleoDAO->devolverEmpleoProvincia();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona de administrador</title>
    <style>
        #estadisticas { width: 500px; margin: 20px auto; }
    </style>
</head>
<body>
    <header>
        <?= "Bienvenido, " . htmlspecialchars($_SESSION['nombre']); ?>
    </header>

    <div id="apartadosAdministrador"></div> 
    
    <div id="estadisticas">
        <h3>Empleos por provincia</h3>
        <canvas id="graficoProvincias"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const datosEstadisticas = <?= json_encode($estadisticas ?? []); ?>;
    </script>

    <script src="../ASSETS/js/estadisticas.js"></script>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit(); // Siempre pon exit después de un header Location
}