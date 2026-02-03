const mapa=L.map("map").setView([41.65,-4.72],7);L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(mapa);
const markersGroup=L.markerClusterGroup();console.log("Total de registros:",datos.length),
datos.forEach(t=>{const a=t.Latitud||t.latitud,o=t.Longitud||t.longitud,r=t.Título||t.titulo||"Sin título";
    if(a&&o){const t=parseFloat(a.toString().replace(",",".")),e=parseFloat(o.toString().replace(",","."));
    if(!isNaN(t)&&!isNaN(e)){const a=L.marker([t,e]).bindPopup(`<b>${r}</b>`);markersGroup.addLayer(a)}}}),
    mapa.addLayer(markersGroup);