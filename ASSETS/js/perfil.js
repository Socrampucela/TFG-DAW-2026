document.addEventListener("DOMContentLoaded", () => {
  const API = window.__PERFIL_API__ || "../ASSETS/API/perfil.php";

  const $ = (id) => document.getElementById(id);

  const toast = $("toast");

  const overlay = $("modalOverlay");
  const modalTitle = $("modalTitle");
  const modalBody = $("modalBody");
  const btnClose = $("modalClose");
  const btnCancel = $("modalCancel");
  const btnOk = $("modalOk");

  const btnOpenNombre = $("btnOpenNombre");
  const btnOpenEmail = $("btnOpenEmail");
  const btnOpenPass = $("btnOpenPass");
  const btnOpenDelete = $("btnOpenDelete");

  let currentModal = null; // "nombre" | "email_request" | "email_confirm" | "pass_request" | "pass_confirm" | "delete"
  let pending = {};

  function showToast(type, msg) {
    toast.classList.remove("hidden");
    toast.className = "mb-4 p-4 rounded-lg border";
    if (type === "ok") {
      toast.classList.add("bg-green-50", "border-green-200", "text-green-800");
    } else {
      toast.classList.add("bg-red-50", "border-red-200", "text-red-800");
    }
    toast.textContent = msg;
  }

 function closeModal() {
  overlay.classList.add("hidden");
  overlay.classList.remove("flex");
  overlay.style.display = "none";   // <-- blindado
  modalBody.innerHTML = "";
  currentModal = null;
  pending = {};
}

function openModal(title, bodyHtml, okText = "Aceptar") {
  modalTitle.textContent = title;
  modalBody.innerHTML = bodyHtml;
  btnOk.textContent = okText;

  overlay.classList.remove("hidden");
  overlay.classList.add("flex");
  overlay.style.display = "flex";   // <-- blindado
}

  async function apiPost(action, payload = {}) {
    const fd = new FormData();
    fd.append("action", action);
    Object.entries(payload).forEach(([k, v]) => fd.append(k, v));

    const res = await fetch(API, { method: "POST", body: fd });
    const data = await res.json().catch(() => null);
    if (!data) throw new Error("Respuesta inválida del servidor.");
    return data;
  }

  // ===== MODALES =====

  btnOpenNombre.addEventListener("click", () => {
    currentModal = "nombre";
    openModal(
      "Cambiar nombre",
      `
        <p class="text-sm text-gray-600 mb-3">
          El cambio se aplica directamente en la base de datos.
        </p>
        <label class="block text-sm font-medium mb-1" for="nuevoNombre">Nuevo nombre y apellidos</label>
        <input id="nuevoNombre" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="text" maxlength="100" placeholder="Ej. Alejandro Moral Rodríguez">
      `,
      "Guardar"
    );
  });

  btnOpenEmail.addEventListener("click", () => {
    currentModal = "email_request";
    openModal(
      "Cambiar email",
      `
        <p class="text-sm text-gray-600 mb-3">
          Por seguridad, enviaremos un código de 6 dígitos al email actual.
        </p>
        <label class="block text-sm font-medium mb-1" for="newEmail">Nuevo email</label>
        <input id="newEmail" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="email" maxlength="150" placeholder="nuevo@email.com">
      `,
      "Enviar código"
    );
  });

  btnOpenPass.addEventListener("click", () => {
    currentModal = "pass_request";
    openModal(
      "Cambiar contraseña",
      `
        <p class="text-sm text-gray-600 mb-3">
          Introduce tu contraseña actual y la nueva. Te enviaremos un código al email actual.
        </p>

        <label class="block text-sm font-medium mb-1" for="currentPass">Contraseña actual</label>
        <input id="currentPass" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="password" maxlength="255">

        <div class="h-3"></div>

        <label class="block text-sm font-medium mb-1" for="newPass">Nueva contraseña (mín. 8)</label>
        <input id="newPass" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="password" maxlength="255">
      `,
      "Enviar código"
    );
  });

  btnOpenDelete.addEventListener("click", () => {
    currentModal = "delete";
    openModal(
      "Eliminar cuenta",
      `
        <p class="text-sm text-gray-700 mb-3">
          Esta acción es <strong>irreversible</strong>.
        </p>

        <label class="block text-sm font-medium mb-1" for="deletePass">Escribe tu contraseña para confirmar</label>
        <input id="deletePass" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="password" maxlength="255">

        <div class="h-3"></div>

        <label class="block text-sm font-medium mb-1" for="deleteWord">Escribe <code>ELIMINAR</code> para confirmar</label>
        <input id="deleteWord" class="w-full border border-gray-300 rounded-md px-3 py-2"
               type="text" maxlength="20" placeholder="ELIMINAR">
      `,
      "Eliminar"
    );
    // botón rojo
    btnOk.classList.remove("bg-black", "hover:bg-gray-800");
    btnOk.classList.add("bg-red-600", "hover:bg-red-700");
  });

  function resetOkButtonStyle() {
    btnOk.classList.remove("bg-red-600", "hover:bg-red-700");
    btnOk.classList.add("bg-black", "hover:bg-gray-800");
  }

  // Cerrar modal
  btnClose.addEventListener("click", () => { resetOkButtonStyle(); closeModal(); });
  btnCancel.addEventListener("click", () => { resetOkButtonStyle(); closeModal(); });
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) { resetOkButtonStyle(); closeModal(); }
  });

  // Aceptar modal (depende del tipo)
  btnOk.addEventListener("click", async () => {
    try {
      if (currentModal === "nombre") {
        const nuevoNombre = $("nuevoNombre").value.trim();
        if (nuevoNombre.length < 2) return showToast("err", "El nombre es demasiado corto.");

        const r = await apiPost("change_name", { nombre_apellido: nuevoNombre });
        if (!r.ok) return showToast("err", r.message || "No se pudo cambiar el nombre.");

        // Actualiza el texto del botón nombre al vuelo
        btnOpenNombre.textContent = nuevoNombre;
        showToast("ok", r.message || "Nombre actualizado.");
        resetOkButtonStyle();
        closeModal();
      }

      if (currentModal === "email_request") {
        const newEmail = $("newEmail").value.trim();
        const r = await apiPost("request_email_change", { new_email: newEmail });
        if (!r.ok) return showToast("err", r.message || "No se pudo enviar el código.");

        currentModal = "email_confirm";
        pending.newEmail = newEmail;

        openModal(
          "Verificar cambio de email",
          `
            <p class="text-sm text-gray-600 mb-3">
              Te hemos enviado un código de 6 dígitos al email actual. Pégalo aquí.
            </p>
            <label class="block text-sm font-medium mb-1" for="codeEmail">Código (6 dígitos)</label>
            <input id="codeEmail" class="w-full border border-gray-300 rounded-md px-3 py-2 tracking-widest"
                   type="text" inputmode="numeric" maxlength="6" placeholder="123456">
          `,
          "Confirmar"
        );
      }

      if (currentModal === "email_confirm") {
        const code = $("codeEmail").value.trim();
        if (!/^\d{6}$/.test(code)) return showToast("err", "El código debe tener 6 dígitos.");

        const r = await apiPost("confirm_email_change", { code });
        if (!r.ok) return showToast("err", r.message || "Código incorrecto o caducado.");

        showToast("ok", r.message || "Email actualizado.");
        resetOkButtonStyle();
        closeModal();
        // recargar para ver email actualizado en tabla
        window.location.reload();
      }

      if (currentModal === "pass_request") {
        const currentPass = $("currentPass").value;
        const newPass = $("newPass").value;

        if (!currentPass || !newPass) return showToast("err", "Rellena ambos campos de contraseña.");
        if (newPass.length < 8) return showToast("err", "La nueva contraseña debe tener al menos 8 caracteres.");

        const r = await apiPost("request_password_change", { current_password: currentPass, new_password: newPass });
        if (!r.ok) return showToast("err", r.message || "No se pudo enviar el código.");

        currentModal = "pass_confirm";
        openModal(
          "Verificar cambio de contraseña",
          `
            <p class="text-sm text-gray-600 mb-3">
              Te hemos enviado un código de 6 dígitos al email actual.
            </p>
            <label class="block text-sm font-medium mb-1" for="codePass">Código (6 dígitos)</label>
            <input id="codePass" class="w-full border border-gray-300 rounded-md px-3 py-2 tracking-widest"
                   type="text" inputmode="numeric" maxlength="6" placeholder="123456">
          `,
          "Confirmar"
        );
      }

      if (currentModal === "pass_confirm") {
        const code = $("codePass").value.trim();
        if (!/^\d{6}$/.test(code)) return showToast("err", "El código debe tener 6 dígitos.");

        const r = await apiPost("confirm_password_change", { code });
        if (!r.ok) return showToast("err", r.message || "Código incorrecto o caducado.");

        showToast("ok", r.message || "Contraseña actualizada.");
        resetOkButtonStyle();
        closeModal();
      }

      if (currentModal === "delete") {
        const pass = $("deletePass").value;
        const word = ($("deleteWord").value || "").trim().toUpperCase();
        if (!pass) return showToast("err", "Introduce tu contraseña.");
        if (word !== "ELIMINAR") return showToast("err", "Debes escribir ELIMINAR para confirmar.");

        const r = await apiPost("delete_account", { password: pass });
        if (!r.ok) return showToast("err", r.message || "No se pudo eliminar la cuenta.");

        // fuera sesión
        window.location.href = "login.php";
      }

    } catch (e) {
      showToast("err", e.message || "Error inesperado.");
    }

  
  });
});



