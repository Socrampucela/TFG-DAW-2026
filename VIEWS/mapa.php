<?php
// Carga de datos (No olvides esto o dará el error de "Undefined variable")
require_once("../config/db.php");
require_once("../DAO/EmpleoDAO.php");
$empleos = (new EmpleoDAO($conn))->obtenerTodos();
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div id="map" style="height: 500px;"></div>

<script>
    // PASO 1: Inyectar datos de PHP a JS (obligatorio antes de cargar el .js externo)
    const datos = <?php echo json_encode($empleos ?? []); ?>;
</script>

<script src="../ASSETS/js/mapa.js"></script>