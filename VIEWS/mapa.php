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

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

  <style>
    /* ===== Layout para que el mapa ocupe entre header y footer ===== */
    html, body { height: 100%; margin: 0; }

    /* 3 filas: header / contenido / footer */
    .page {
      min-height: 100dvh;
      display: grid;
      grid-template-rows: auto 1fr auto;
    }

    /* Importante: permite que el hijo (mapa) calcule bien el alto */
    .page-main {
      min-height: 0;
    }

    /* Mapa ocupa TODO el alto disponible en la fila central */
    #map {
      width: 100%;
      height: 100%;
      min-height: 400px; /* seguridad si en algún dispositivo queda poco alto */
      background: #eee;
      border-bottom: 1px solid #ccc;
      z-index: 10;
    }

    @media (max-width: 640px) {
      #map {
        min-height: 350px;
      }
      .leaflet-popup-content {
        font-size: 14px;
        width: 180px !important;
        text-align: center;
      }
    }

    /* Botón dentro del popup */
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

<body class="bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]">

  <div class="page">
    <?php include_once '../includes/header.php'; ?>

    <main class="page-main">
      <div id="map"></div>
    </main>

    <?php include_once '../includes/footer.php'; ?>
  </div>

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

</body>
</html>
