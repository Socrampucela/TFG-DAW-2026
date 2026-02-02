const modal = document.getElementById("perfilModal");
const abrirBtn = document.getElementById("abrirModalPerfil");
const cerrarBtn = document.getElementById("cerrarModalPerfil");
const overlay = document.getElementById("overlayPerfil");

const msgBox = document.getElementById("perfilMsg");

const formNombre = document.getElementById("formNombre");
const formSendCode = document.getElementById("formSendCode");
const formVerify = document.getElementById("formVerify");

const selectTipo = document.getElementById("tipoCambio");
const inputNewEmail = document.getElementById("newEmail");
const inputNewPassword = document.getElementById("newPassword");
const wrapEmail = document.getElementById("wrapEmail");
const wrapPassword = document.getElementById("wrapPassword");

const stepNewValue = document.getElementById("stepNewValue");
const stepCode = document.getElementById("stepCode");

function showMsg(text, ok = true) {
  msgBox.textContent = text;
  msgBox.className = ok
    ? "mt-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm"
    : "mt-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm";
  msgBox.classList.remove("hidden");
}

function clearMsg() {
  msgBox.classList.add("hidden");
  msgBox.textContent = "";
}

function openModal() {
  clearMsg();
  modal.classList.remove("hidden");
  overlay.classList.remove("hidden");
  document.body.classList.add("overflow-hidden");

  stepNewValue.classList.remove("hidden");
  stepCode.classList.add("hidden");
  formVerify.reset();
}

function closeModal() {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
  document.body.classList.remove("overflow-hidden");
  clearMsg();
}

abrirBtn?.addEventListener("click", openModal);
cerrarBtn?.addEventListener("click", closeModal);
overlay?.addEventListener("click", closeModal);

selectTipo?.addEventListener("change", () => {
  const t = selectTipo.value;
  if (t === "email") {
    wrapEmail.classList.remove("hidden");
    wrapPassword.classList.add("hidden");
  } else {
    wrapPassword.classList.remove("hidden");
    wrapEmail.classList.add("hidden");
  }
  clearMsg();
});

formNombre?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMsg();

  const fd = new FormData(formNombre);
  fd.append("action", "update_name");

  const res = await fetch("../ASSETS/API/perfil.php", { method: "POST", body: fd });
  const data = await res.json();

  if (!data.ok) return showMsg(data.message, false);

  showMsg(data.message, true);

  const spanNombre = document.getElementById("nombreHeader");
  if (spanNombre) spanNombre.textContent = fd.get("nombre_apellido");
});

formSendCode?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMsg();

  const tipo = selectTipo.value;

  const fd = new FormData();
  fd.append("action", "send_code");
  fd.append("type", tipo);

  if (tipo === "email") {
    fd.append("new_email", inputNewEmail.value.trim());
  } else {
    fd.append("new_password", inputNewPassword.value);
  }

  const res = await fetch("../ASSETS/API/perfil.php", { method: "POST", body: fd });
  const data = await res.json();

  if (!data.ok) return showMsg(data.message, false);

  showMsg(data.message, true);

  stepNewValue.classList.add("hidden");
  stepCode.classList.remove("hidden");
});

formVerify?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearMsg();

  const fd = new FormData(formVerify);
  fd.append("action", "verify_and_apply");

  const res = await fetch("../ASSETS/API/perfil.php", { method: "POST", body: fd });
  const data = await res.json();

  if (!data.ok) return showMsg(data.message, false);

  showMsg(data.message, true);

  // Si es email, recargar para ver sesión actualizada bien
  if (selectTipo.value === "email") {
    setTimeout(() => location.reload(), 600);
  } else {
    // password: reset y volver al paso 1
    formSendCode.reset();
    formVerify.reset();
    stepCode.classList.add("hidden");
    stepNewValue.classList.remove("hidden");
  }
});

// init
if (selectTipo) selectTipo.dispatchEvent(new Event("change"));
