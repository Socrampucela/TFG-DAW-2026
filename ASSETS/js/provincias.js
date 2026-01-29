let selectProvincia = document.getElementById("select-provincia");
let selectLocalidad = document.getElementById("select-localidad");

selectProvincia.addEventListener("change", buscarLocalidades);

async function buscarLocalidades() {
    const provinciaID = selectProvincia.value;

    selectLocalidad.innerHTML = '<option value="">Cargando...</option>';
    selectLocalidad.disabled = true;

    if (provinciaID !== "") {
        try {
            let peticion = await fetch("../ASSETS/API/municipios.php?provincia_id=" + provinciaID);
            let respuestas = await peticion.json();

            selectLocalidad.innerHTML = '<option value="">Selecciona una localidad</option>';
            
            respuestas.forEach(respuesta => {
                let option = document.createElement("option");
                option.value = respuesta.Municipio;          // 👈 filtro por texto (lo que hay en tu tabla ofertas)
option.text = respuesta.Municipio;
option.dataset.cod = respuesta.Cod_Municipio; // opcional, por si te sirve

                selectLocalidad.appendChild(option);
            });

            selectLocalidad.disabled = false;

        } catch (error) {
            console.error("Error al cargar localidades:", error);
            selectLocalidad.innerHTML = '<option value="">Error al cargar</option>';
        }
    } else {
        selectLocalidad.innerHTML = '<option value="">Selecciona primero una provincia</option>';
        selectLocalidad.disabled = true;
    }
}