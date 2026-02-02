document.addEventListener("DOMContentLoaded", () => {
  const $ = (id) => document.getElementById(id);

  const fTitulo = $("fTitulo");
  const selProv = $("select-provincia");
  const selLoc = $("select-localidad");
  const fDias = $("fDias");
  const btnReset = $("btnReset");

  const totalText = $("totalText");
  const statusText = $("statusText");
  const grid = $("grid");

  const pager = $("pager");
  const prevBtn = $("prevBtn");
  const nextBtn = $("nextBtn");
  const pageSelect = $("pageSelect");
  const pagesText = $("pagesText");
  const rangeText = $("rangeText");

  const API_EMPLEOS = "../ASSETS/API/empleo.php";
  const API_MUN = "../ASSETS/API/municipios.php";

  let page = 1;
  const pageSize = 18;
  let total = 0;
  let totalPages = 1;

  let ctrlEmpleos = null;
  let ctrlMun = null;

  const esc = (s) =>
    String(s ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");

  // Equivalente a: htmlspecialchars(strip_tags(...))
  const stripTags = (html) =>
    String(html ?? "")
      .replace(/<[^>]*>/g, " ")
      .replace(/\s+/g, " ")
      .trim();

  // Formato similar a PHP: date('d M', strtotime(...))
  const formatDM = (iso) => {
    const d = new Date(String(iso ?? ""));
    if (Number.isNaN(d.getTime())) return "";
    return d.toLocaleDateString("es-ES", { day: "2-digit", month: "short" });
  };

  const debounce = (fn, ms = 250) => {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), ms);
    };
  };

  const provinciaNombre = () => {
    const opt = selProv.options[selProv.selectedIndex];
    return opt ? opt.textContent.trim() : "";
  };

  const setPager = () => {
    pager.classList.toggle("hidden", totalPages <= 1);

    pageSelect.innerHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      const o = document.createElement("option");
      o.value = String(i);
      o.textContent = String(i);
      if (i === page) o.selected = true;
      pageSelect.appendChild(o);
    }

    pagesText.textContent = `de ${totalPages}`;
    prevBtn.disabled = page <= 1;
    nextBtn.disabled = page >= totalPages;

    const start = total === 0 ? 0 : (page - 1) * pageSize + 1;
    const end = Math.min(page * pageSize, total);
    rangeText.textContent = `Mostrando ${start}-${end} de ${total}`;
  };

const card = (e) => {
  const categoria = esc(e["Categoría"] ?? "General");
  const fecha = esc(formatDM(e["Fecha publicación"] ?? ""));
  const titulo = esc(e["Título"] ?? "");
  const localidad = esc(e["Localidad"] ?? "");
  const link = e["Enlace al contenido"] ?? "";

  const descText = stripTags(e["Descripción"] ?? "");
  const descShort = descText.length > 220 ? descText.slice(0, 220) + "…" : descText;

  return `
    <article class="job-card min-h-[250px]">
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-[#3882B6] bg-blue-50 px-2 py-1 rounded">
            ${categoria}
          </span>
          <span class="text-gray-400 text-xs">
            ${fecha}
          </span>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
          ${titulo}
        </h2>

        <p class="text-[#3882B6] font-semibold text-sm mb-3">
          ${localidad}
        </p>

        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
          ${esc(descShort)}
        </p>
      </div>

      <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        ${link ? `
          <a href="${esc(link)}" target="_blank"
             class="text-[#3882B6] font-bold text-sm hover:text-blue-800 transition-colors">
            Ver detalles →
          </a>
        ` : ""}
      </div>
    </article>
  `;
};

  async function cargarEmpleos() {
    if (ctrlEmpleos) ctrlEmpleos.abort();
    ctrlEmpleos = new AbortController();

    statusText.textContent = "Cargando…";

    const params = new URLSearchParams({
      titulo: fTitulo.value.trim(),
      provincia: selProv.value
        ? selProv.options[selProv.selectedIndex].textContent.trim()
        : "",
      localidad: selLoc.disabled ? "" : selLoc.value,
      dias: fDias.value,
      page: String(page),
      pageSize: String(pageSize)
    });

    try {
      const r = await fetch(`${API_EMPLEOS}?${params}`, { signal: ctrlEmpleos.signal });
      const data = await r.json();

      const items = Array.isArray(data.items) ? data.items : [];
      total = Number(data.total || 0);
      totalPages = Number(data.totalPages || 1);

      totalText.textContent = `${total} ofertas encontradas`;
      grid.innerHTML = items.map(card).join("") ||
        `<div class="col-span-full text-center text-gray-500 py-10">No hay resultados con esos filtros.</div>`;

      setPager();
      statusText.textContent = "";
    } catch (e) {
      if (e.name === "AbortError") return;
      statusText.textContent = "Error cargando resultados.";
      totalText.textContent = "Ups, algo ha salido mal";
    }
  }

  async function cargarLocalidades() {
    selLoc.disabled = true;
    selLoc.innerHTML = `<option value="">Cargando…</option>`;

    if (!selProv.value) {
      selLoc.innerHTML = `<option value="">Selecciona primero una provincia</option>`;
      selLoc.disabled = true;
      return;
    }

    if (ctrlMun) ctrlMun.abort();
    ctrlMun = new AbortController();

    try {
      const r = await fetch(`${API_MUN}?provincia_id=${encodeURIComponent(selProv.value)}`, { signal: ctrlMun.signal });
      const data = await r.json();

      const arr = Array.isArray(data) ? data : [];

      selLoc.innerHTML = `<option value="">Todas</option>` +
        arr.map(m => `<option value="${esc(m.Municipio)}">${esc(m.Municipio)}</option>`).join("");

      selLoc.disabled = false;
    } catch (e) {
      if (e.name === "AbortError") return;
      selLoc.innerHTML = `<option value="">(Error cargando)</option>`;
      selLoc.disabled = true;
    }
  }

  const buscarDebounced = debounce(() => { page = 1; cargarEmpleos(); }, 250);

  fTitulo.addEventListener("input", buscarDebounced);

  selProv.addEventListener("change", async () => {
    page = 1;
    await cargarLocalidades();
    cargarEmpleos();
  });

  selLoc.addEventListener("change", () => { page = 1; cargarEmpleos(); });
  fDias.addEventListener("change", () => { page = 1; cargarEmpleos(); });

  btnReset.addEventListener("click", (e) => {
    e.preventDefault();
    fTitulo.value = "";
    selProv.value = "";
    fDias.value = "0";
    selLoc.innerHTML = `<option value="">Selecciona primero una provincia</option>`;
    selLoc.disabled = true;
    page = 1;
    cargarEmpleos();
  });

  prevBtn.addEventListener("click", () => { if (page > 1) { page--; cargarEmpleos(); } });
  nextBtn.addEventListener("click", () => { if (page < totalPages) { page++; cargarEmpleos(); } });
  pageSelect.addEventListener("change", () => { page = Number(pageSelect.value) || 1; cargarEmpleos(); });

  cargarEmpleos();
});
