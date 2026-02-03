<?php
// 1. Carga de datos
require_once("../config/db.php");
require_once("../DAO/EmpleoDAO.php"); 
$empleos = (new EmpleoDAO($conn))->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Empleos</title>
    <?php include_once '../includes/header.php'; ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    
    <style>
        /* Estilos unificados para el mapa */
        #map { 
            width: 100%; 
            height: 70vh; /* Ocupa el 70% de la altura de la pantalla */
            min-height: 400px; 
            max-height: 800px;
            background: #eee; 
            border-bottom: 1px solid #ccc;
            z-index: 10; 
        }
        @media (max-width: 640px) {
            #map { 
                height: 50vh !important; 
                min-height: 350px;
            }

            .leaflet-popup-content { 
                font-size: 14px; 
                width: 180px !important; 
                text-align: center;
            }
        }

        /* Estilo para el botón dentro del popup */
        .popup-link {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 12px;
            background-color: #2563eb;
            color: white !important;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
</head>
<body class="bg-gray-50">

    <div id="map"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("--- INICIANDO MAPA DE EMPLEOS ---");

            const datos = <?php echo json_encode($empleos ?? []); ?>;
            
            if (!datos || datos.length === 0) {
                console.error("No se encontraron datos de empleos.");
                return;
            }

            const mapa = L.map('map').setView([41.65, -4.72], 7); 
            
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapa);

            const markersGroup = L.markerClusterGroup();
            let marcadoresValidos = 0;

            datos.forEach((e, index) => {

                let latRaw = e.Latitud || e.latitud || e.LATITUD; 
                let lonRaw = e.Longitud || e.longitud || e.LONGITUD;
                let titulo = e.Título || e.titulo || "Oferta de Empleo";
                let url = e['Enlace al contenido'] || e['Enlace al contenido '] || '#';

                if (latRaw && lonRaw) {

                    let lat = parseFloat(String(latRaw).replace(',', '.'));
                    let lon = parseFloat(String(lonRaw).replace(',', '.'));

                    if (!isNaN(lat) && !isNaN(lon) && lat !== 0) {
                        const marker = L.marker([lat, lon]);

                        marker.bindPopup(`
                            <div class="p-2">
                                <h3 style="margin:0 0 8px 0; font-size:16px;">${titulo}</h3>
                                <a href="${url}" target="_blank" class="popup-link">Ver detalles</a>
                            </div>
                        `);
                        
                        markersGroup.addLayer(marker);
                        marcadoresValidos++;
                    }
                }
            });

            mapa.addLayer(markersGroup);
            if (marcadoresValidos > 0) {
                mapa.fitBounds(markersGroup.getBounds(), { padding: [20, 20] });
            }

            setTimeout(() => {
                mapa.invalidateSize();
            }, 500);
        });
    </script>

    <?php include_once '../includes/footer.php'; ?>
</body>
</html>