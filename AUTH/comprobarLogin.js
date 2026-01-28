const formulario = document.getElementById("formulario");
const divErrores = document.getElementById("divError");

if (formulario && divErrores) {
  formulario.addEventListener("submit", (e) => {
    divErrores.innerHTML = "";

    const errores = [];

    const errEmail = validarEmail(document.getElementById("email"));
    if (errEmail) errores.push(errEmail);

    const errPassword = validarPassword(document.getElementById("password"));
    if (errPassword) errores.push(errPassword);

    const errCaptcha = validarCaptcha();
    if (errCaptcha) errores.push(errCaptcha);

    if (errores.length > 0) {
      e.preventDefault();
      errores.forEach(texto => {
        const p = document.createElement("p");
        p.textContent = texto;
        p.classList.add("text-red-600", "text-sm", "mb-1");
        divErrores.appendChild(p);
      });
    }
  });
}

/* =========================
   VALIDACIONES
   ========================= */

function validarEmail(inputEmail) {
  let error = null;

  if (!inputEmail) {
    error = "El correo electrónico es obligatorio.";
  } else {
    const valor = inputEmail.value.trim();
    const regex = /^[^@]+@[^@]+\.[^@]+$/;

    if (valor === "") {
      error = "El correo electrónico es obligatorio.";
    } else if (!regex.test(valor)) {
      error = "El formato del correo electrónico no es válido.";
    }
  }

  return error;
}

function validarPassword(inputPassword) {
  let error = null;

  if (!inputPassword) {
    error = "La contraseña es obligatoria.";
  } else {
    const valor = inputPassword.value;

    if (valor.trim() === "") {
      error = "La contraseña es obligatoria.";
    } else if (valor.length < 6) {
      error = "La contraseña debe tener al menos 6 caracteres.";
    }
  }

  return error;
}

function validarCaptcha() {
  let error = null;

  const respuestaCaptcha = document.querySelector('[name="cf-turnstile-response"]');
  if (!respuestaCaptcha || !respuestaCaptcha.value) {
    error = "Debes completar el captcha para continuar.";
  }

  return error;
}
