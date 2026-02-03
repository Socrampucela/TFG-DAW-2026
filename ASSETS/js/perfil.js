document.addEventListener("DOMContentLoaded", () => {
  const $ = (id) => document.getElementById(id);

  const overlay = $("modalOverlay");
  const title = $("modalTitle");
  const body = $("modalBody");
  const btnClose = $("modalClose");
  const btnCancel = $("modalCancel");
  const btnOk = $("modalOk");
  const toast = $("toast");

  const tdNombre = $("tdNombre");
  const tdEmail = $("tdEmail");

  const btnEdit = $("btnEditPerfil");     // ✏️
  const btnDelete = $("btnDeletePerfil"); // 🗑️

  let step = null; // "edit" | "code" | "delete"

  function showToast(msg, type = "ok") {
    if (!toast) return;

    toast.classList.remove("hidden");
    toast.className = "mb-4 p-4 rounded-lg border";

    if (type === "ok") toast.classList.add("bg-green-50", "border-green-200", "text-green-800");
    if (type === "err") toast.classList.add("bg-red-50", "border-red-200", "text-red-800");
    if (type === "info") toast.classList.add("bg-blue-50", "border-blue-200", "text-blue-800");

    toast.textContent = msg;

    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(() => toast.classList.add("hidden"), 3500);
  }

  function openModal({ t, html, okText = "Aceptar", danger = false }) {
    title.textContent = t;
    body.innerHTML = html;

    btnOk.textContent = okText;
    btnOk.classList.toggle("bg-black", !danger);
    btnOk.classList.toggle("hover:bg-gray-800", !danger);
    btnOk.classList.toggle("bg-red-600", danger);
    btnOk.classList.toggle("hover:bg-red-700", danger);

    overlay.classList.remove("hidden");
    overlay.classList.add("flex");
    btnCancel.focus();
  }

  function closeModal() {
    overlay.classList.add("hidden");
    overlay.classList.remove("flex");
    body.innerHTML = "";
    step = null;
  }

  function openEditModal() {
    step = "edit";

    openModal({
      t: "Editar perfil",
      html: `
        <div class="space-y-4">
          <div>
            <label class="text-sm text-gray-700">Nombre</label>
            <input id="pNombre"
                   class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2"
                   type="text"
                   maxlength="80"
                   autocomplete="name" />
          </div>

          <div>
            <label class="text-sm text-gray-700">Nuevo email</label>
            <input id="pEmail"
                   class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2"
                   type="email"
                   autocomplete="email" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="text-sm text-gray-700">Nueva contraseña</label>
              <input id="pPass1"
                     class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2"
                     type="password"
                     autocomplete="new-password" />
            </div>
            <div>
              <label class="text-sm text-gray-700">Repite contraseña</label>
              <input id="pPass2"
                     class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2"
                     type="password"
                     autocomplete="new-password" />
            </div>
          </div>

          <p class="text-xs text-gray-500">
            Al guardar, te enviaremos un código al email actual para confirmar todos los cambios.
          </p>
        </div>
      `,
      okText: "Guardar"
    });

    const inpNombre = $("pNombre");
    if (inpNombre && tdNombre) {
      inpNombre.value = tdNombre.textContent.trim();
      inpNombre.focus();
      inpNombre.select();
    }
  }

  function openCodeModal() {
    step = "code";

    openModal({
      t: "Confirmar cambios",
      html: `
        <p class="text-sm text-gray-600 mb-4">
          Te hemos enviado un <strong>código de 6 dígitos</strong> al email actual.
        </p>
        <label class="text-sm text-gray-700">Código</label>
        <input id="pCode"
               class="mt-2 w-full border border-gray-300 rounded-md px-3 py-2"
               inputmode="numeric"
               maxlength="6"
               placeholder="123456" />
      `,
      okText: "Confirmar"
    });

    $("pCode")?.focus();
  }

  function openDeleteModal() {
    step = "delete";

    openModal({
      t: "Eliminar cuenta",
      html: `
        <p class="text-sm text-gray-700">
          Esta acción es <strong>irreversible</strong>.
        </p>
        <p class="mt-2 text-sm text-gray-600">
          ¿Seguro que quieres eliminar tu cuenta?
        </p>
      `,
      okText: "Eliminar",
      danger: true
    });
  }

  btnEdit?.addEventListener("click", openEditModal);
  btnDelete?.addEventListener("click", openDeleteModal);

  btnOk?.addEventListener("click", async () => {
    const api = window.__PERFIL_API__ || "../ASSETS/API/perfil.php";

    btnOk.disabled = true;
    btnOk.classList.add("opacity-60", "cursor-not-allowed");

    try {
      // -------------------------
      // EDITAR PERFIL -> START_UPDATE
      // -------------------------
      if (step === "edit") {
        const nombre = ($("pNombre")?.value || "").trim();
        const email = ($("pEmail")?.value || "").trim();
        const pass1 = $("pPass1")?.value || "";
        const pass2 = $("pPass2")?.value || "";

        if (nombre.length < 2 || nombre.length > 80) {
          showToast("El nombre debe tener entre 2 y 80 caracteres.", "err");
          return;
        }

        // ✅ Contraseña (misma lógica y mensajes que comprobarRegistro.js)
        if (pass1.trim() !== "" || pass2.trim() !== "") {
          if (pass1.trim() === "") {
            showToast("La contraseña es obligatoria.", "err");
            return;
          } else if (pass1.length < 6) {
            showToast("La contraseña debe tener al menos 6 caracteres.", "err");
            return;
          } else if (pass2.trim() === "") {
            showToast("Debes repetir la contraseña.", "err");
            return;
          } else if (pass1 !== pass2) {
            showToast("Las contraseñas no coinciden.", "err");
            return;
          }
        }

        const res = await fetch(api, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({
            accion: "start_update",
            nombre,
            email,
            password: pass1 || ""
          })
        });

        const data = await res.json().catch(() => null);

        if (!res.ok || !data || data.ok !== true) {
          showToast(data?.error || "No se pudo iniciar la verificación.", "err");
          return;
        }

        showToast(data.msg || "Código enviado al email actual.", "ok");
        openCodeModal();
        return;
      }

      // -------------------------
      // CONFIRMAR CAMBIOS -> CONFIRM_UPDATE
      // -------------------------
      if (step === "code") {
        const code = ($("pCode")?.value || "").trim();

        if (!/^\d{6}$/.test(code)) {
          showToast("El código debe tener 6 dígitos.", "err");
          return;
        }

        const res = await fetch(api, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({ accion: "confirm_update", code })
        });

        const data = await res.json().catch(() => null);

        if (!res.ok || !data || data.ok !== true) {
          showToast(data?.error || "Código incorrecto o caducado.", "err");
          return;
        }

        if (data.nombre && tdNombre) tdNombre.textContent = data.nombre;
        if (data.email && tdEmail) tdEmail.textContent = data.email;

        closeModal();
        showToast(data.msg || "Cambios aplicados correctamente.", "ok");
        return;
      }

      // -------------------------
      // ELIMINAR CUENTA -> DELETE
      // -------------------------
      if (step === "delete") {
        const res = await fetch(api, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({ accion: "delete" })
        });

        const data = await res.json().catch(() => null);

        if (!res.ok || !data || data.ok !== true) {
          showToast(data?.error || "No se pudo eliminar la cuenta.", "err");
          return;
        }

        showToast(data.msg || "Cuenta eliminada correctamente.", "ok");
        setTimeout(() => {
          window.location.href = "login.php";
        }, 800);
        return;
      }
    } catch (e) {
      showToast("Error de red.", "err");
    } finally {
      btnOk.disabled = false;
      btnOk.classList.remove("opacity-60", "cursor-not-allowed");
    }
  });

  // cerrar
  btnClose?.addEventListener("click", closeModal);
  btnCancel?.addEventListener("click", closeModal);
  overlay?.addEventListener("click", (e) => { if (e.target === overlay) closeModal(); });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !overlay.classList.contains("hidden")) closeModal();
  });
});