<?php
// 1. Carga de datos
require_once("../config/db.php");
require_once("../DAO/EmpleoDAO.php"); // Ojo: Revisa si es EmpleoDAO o empleoDAO
$empleos = (new EmpleoDAO($conn))->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once '../includes/header.php'; ?>
    <meta charset="UTF-8">
    <title>Mapa de Empleos</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <style>
        #map { width: 100%; height: 500px; background: #eee; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <div id="map"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("--- INICIANDO DEBUG DEL MAPA ---");

            const datos = <?php echo json_encode($empleos ?? []); ?>;
            console.log("Datos recibidos de PHP:", datos);

            if (!datos || datos.length === 0) {
                console.error("¡ERROR! No hay datos. Revisa tu consulta SQL.");
                return;
            }

            // 2. Iniciamos el mapa
            // Centrado en España aprox
            const mapa = L.map('map').setView([40.416, -3.703], 6); 
            
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapa);

            const markersGroup = L.markerClusterGroup();
            let marcadoresValidos = 0;

            datos.forEach((e, index) => {

                let latRaw = e.Latitud || e.latitud || e.LATITUD; 
                let lonRaw = e.Longitud || e.longitud || e.LONGITUD;
                let titulo = e.Título || e.titulo || e.TITULO || "Oferta sin título";
                let url = e['Enlace al contenido'] || e['enlace'] || '#';

                // Depuración del primer elemento para ver qué claves llegan
                if (index === 0) {
                    console.log("Primer registro crudo:", e);
                    console.log("Intentando leer latitud:", latRaw);
                }

                if (latRaw && lonRaw) {
                    // Convertir "41,65" a "41.65" y a número
                    let lat = parseFloat(String(latRaw).replace(',', '.'));
                    let lon = parseFloat(String(lonRaw).replace(',', '.'));

                    if (!isNaN(lat) && !isNaN(lon) && lat !== 0) {
                        const marker = L.marker([lat, lon]);
                        
                        marker.bindPopup(`
                            <div style="text-align:center;">
                                <b>${titulo}</b><br>
                                <a href="${url}" target="_blank">Ver oferta</a>
                            </div>
                        `);
                        
                        markersGroup.addLayer(marker);
                        marcadoresValidos++;
                    }
                }
            });

            console.log("Marcadores añadidos al mapa:", marcadoresValidos);

            // 5. Añadir clusters al mapa
            mapa.addLayer(markersGroup);
            
            // Truco: Si hay marcadores, ajusta el zoom para verlos todos
            if (marcadoresValidos > 0) {
                mapa.fitBounds(markersGroup.getBounds());
            }
        });
    </script>
    <?php include_once '../includes/footer.php'; ?>
</body>
</html>