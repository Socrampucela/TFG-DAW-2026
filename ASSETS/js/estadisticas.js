document.addEventListener("DOMContentLoaded",()=>{function a(a){t&&t.destroy(),"provincia"===a?t=
    new Chart(e,{type:"pie",data:{labels:datosProvincias.map(a=>a.provincia),datasets:[{data:datosProvincias.map(a=>a.total),
        backgroundColor:["#4e73df","#1cc88a","#36b9cc","#f6c23e","#e74a3b"]}]},options:{maintainAspectRatio:!1}}):
        "fecha"===a&&(t=new Chart(e,{type:"bar",data:{labels:datosFechas.map(a=>a.fecha),datasets:[{label:"Ofertas Publicadas",
        data:datosFechas.map(a=>a.total),backgroundColor:"#4e73df",borderRadius:5}]},
        options:{maintainAspectRatio:!1,
        scales:{y:{beginAtZero:!0,ticks:{stepSize:1}}}}}))}
        let t;
        const e=document.getElementById("graficoAdmin").getContext("2d");
        document.getElementById("selectorGrafico").addEventListener("change",t=>{a(t.target.value)}),a("provincia")});