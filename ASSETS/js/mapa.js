const mapa = L.map('map').setView([41.65, -4.72], 7);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
const markersGroup = L.markerClusterGroup();

console.log("Total de registros:", datos.length);

datos.forEach(e => {
    // IMPORTANTE: Prueba con minúsculas si con mayúsculas no va
    const latRaw = e.Latitud || e.latitud; 
    const lonRaw = e.Longitud || e.longitud;
    const titulo = e.Título || e.titulo || "Sin título";

    if (latRaw && lonRaw) {
        const lat = parseFloat(latRaw.toString().replace(',', '.'));
        const lon = parseFloat(lonRaw.toString().replace(',', '.'));

        if (!isNaN(lat) && !isNaN(lon)) {
            const marker = L.marker([lat, lon])
                .bindPopup(`<b>${titulo}</b>`);
            markersGroup.addLayer(marker);
        }
    }
});

mapa.addLayer(markersGroup);