const divErrores = document.getElementById("divError")
document.getElementById('formulario').addEventListener("submit",(e)=>{
const respuestaCaptcha = document.querySelector('[name="cf-turnstile-response"]')
if(!respuestaCaptcha || !respuestaCaptcha.value){
    e.preventDefault()
    let parrafoError = document.createElement("p")
    let textoErrorCaptcha = "Debes completar el captcha para continuar"
    parrafoError.textContent = textoErrorCaptcha;
    divErrores.appendChild(parrafoError);
    return false;
}
})
