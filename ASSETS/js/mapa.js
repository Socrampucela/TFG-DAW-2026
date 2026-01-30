const mapa = L.map('map').setView([41.65, -4.72], 7);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

datos.forEach(e => {
    if (e.Latitud && e.Longitud) {
        const lat = parseFloat(e.Latitud.toString().replace(',', '.'));
        const lon = parseFloat(e.Longitud.toString().replace(',', '.'));

        if (!isNaN(lat) && !isNaN(lon) && lat !== 0) {
            const url = e['Enlace al contenido'];
            L.marker([lat, lon])
             .addTo(mapa)
             .bindPopup(`
                <div style="line-height: 1.5;">
                    <b style="color: #2c3e50;">${e.Título}</b><br>
                    <span style="color: #7f8c8d;">${e.Localidad}</span><br>
                    <a href="${url}" target="_blank" style="color: #3498db; font-weight: bold; text-decoration: none;">
                        Ver oferta de empleo →</a></div>
             `);
        }
    }
});