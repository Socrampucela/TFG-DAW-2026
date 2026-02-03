async function buscarLocalidades(){
    const e=selectProvincia.value;
    if(selectLocalidad.innerHTML='<option value="">Cargando...</option>',selectLocalidad.disabled=!0,""!==e)
        try{let a=await fetch("../ASSETS/API/municipios.php?provincia_id="+e),i=await a.json();
    selectLocalidad.innerHTML='<option value="">Selecciona una localidad</option>',
    i.forEach(e=>{let a=document.createElement("option");a.value=e.Cod_Municipio,a.text=e.Municipio,selectLocalidad.appendChild(a)}),
    selectLocalidad.disabled=!1}catch(e){console.error("Error al cargar localidades:",e),
        selectLocalidad.innerHTML='<option value="">Error al cargar</option>'}
        else selectLocalidad.innerHTML='<option value="">Selecciona primero una provincia</option>',selectLocalidad.disabled=!0}
        let selectProvincia=document.getElementById("select-provincia"),
        selectLocalidad=document.getElementById("select-localidad");
    selectProvincia.addEventListener("change",buscarLocalidades);