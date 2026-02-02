
if (typeof datosEstadisticas !== 'undefined' && datosEstadisticas.length > 0) {
    

    const etiquetas = datosEstadisticas.map(item => item.provincia || 'Sin Provincia');
    const valores = datosEstadisticas.map(item => item.total);

    const ctx = document.getElementById('graficoProvincias').getContext('2d');
    
    new Chart(ctx, {
        type: 'pie', 
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Ofertas de empleo',
                data: valores,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                    '#9966FF', '#FF9F40', '#FFCD56', '#C9CBCF'
                ],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
} else {
    console.error("No hay datos disponibles para generar el gráfico.");
}