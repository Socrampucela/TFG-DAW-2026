document.addEventListener("DOMContentLoaded",()=>{
let miGrafico; // Variable global para la instancia
const ctx = document.getElementById('graficoAdmin').getContext('2d');

function renderizarGrafico(tipo) {
    // Si ya existe un gráfico, lo borramos para poder pintar el nuevo
    if (miGrafico) {
        miGrafico.destroy();
    }

    if (tipo === 'provincia') {
        miGrafico = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: datosProvincias.map(i => i.provincia),
                datasets: [{
                    data: datosProvincias.map(i => i.total),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            },
            options: { maintainAspectRatio: false }
        });
    } else if (tipo === 'fecha') {
        miGrafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datosFechas.map(i => i.fecha),
                datasets: [{
                    label: 'Ofertas Publicadas',
                    data: datosFechas.map(i => i.total),
                    backgroundColor: '#4e73df',
                    borderRadius: 5
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }
}

// Escuchar cambios en el desplegable
document.getElementById('selectorGrafico').addEventListener('change', (e) => {
    renderizarGrafico(e.target.value);
});

// Carga inicial (por defecto el de provincias)
renderizarGrafico('provincia');
})
