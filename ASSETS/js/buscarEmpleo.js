(() => {
  // Guard: si por lo que sea se carga dos veces, no re-inicializa
  if (window.__buscarEmpleoInit) return;
  window.__buscarEmpleoInit = true;

// ASSETS/js/buscarEmpleo.js

// Inputs / selects
const fTitulo = document.getElementById("fTitulo");
const fDias = document.getElementById("fDias");

const selectProvincia = document.getElementById("select-provincia");
const selectLocalidad = document.getElementById("select-localidad");

const btnReset = document.getElementById("btnReset");

// UI
const grid = document.getElementById("grid");
const pager = document.getElementById("pager");

const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const pageSelect = document.getElementById("pageSelect");
const pagesText = document.getElementById("pagesText");
const rangeText = document.getElementById("rangeText");

const totalText = document.getElementById("totalText");
const statusText = document.getElementById("statusText");

// Estado
let state = {
  page: 1,
  pageSize: 20,
  total: 0,
  totalPages: 1
};

// Util
function debounce(fn, ms = 300) {
  let t;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), ms);
  };
}

function escapeHtml(str) {
  return String(str ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function formatDate(iso) {
  // Fecha publicación esperada: YYYY-MM-DD (aunque venga como string)
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return "";
  return d.toLocaleDateString("es-ES", { day: "2-digit", month: "short" });
}

// Lee provincia como NOMBRE (no código)
function getProvinciaNombre() {
  const opt = selectProvincia?.options?.[selectProvincia.selectedIndex];
  return opt?.dataset?.nombre ? opt.dataset.nombre.trim() : "";
}

// Localidad ya viene como TEXTO (si aplicaste el cambio en provincias.js)
function getLocalidadNombre() {
  return (selectLocalidad?.value || "").trim();
}

function buildQuery(page = 1) {
  const params = new URLSearchParams();
  params.set("page", page);
  params.set("pageSize", state.pageSize);

  const titulo = fTitulo.value.trim();
  const provincia = getProvinciaNombre();
  const localidad = getLocalidadNombre();
  const dias = (fDias.value || "0").trim();

  if (titulo) params.set("titulo", titulo);
  if (provincia) params.set("provincia", provincia);
  if (localidad) params.set("localidad", localidad);

  if (dias !== "0") params.set("dias", dias);

  return params.toString();
}

function renderCards(items) {
  grid.innerHTML = "";

  if (!items || !items.length) {
    grid.innerHTML = `
      <div class="col-span-full bg-white border border-gray-100 rounded-2xl p-8 text-center text-gray-500">
        No hay resultados con esos filtros.
      </div>`;
    return;
  }

  for (const empleo of items) {
    const titulo = escapeHtml(empleo["Título"]);
    const localidad = escapeHtml(empleo["Localidad"]);
    const fecha = formatDate(empleo["Fecha publicación"]);
    const enlace = empleo["Enlace al contenido"] || "#";
    const descripcion = escapeHtml((empleo["Descripción"] || "").replace(/<[^>]*>/g, ""));

    const card = document.createElement("article");
    card.className = "job-card min-h-[250px]";
    card.innerHTML = `
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-[#3882B6] bg-blue-50 px-2 py-1 rounded">
            GENERAL
          </span>
          <span class="text-gray-400 text-xs">${escapeHtml(fecha)}</span>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">${titulo}</h2>
        <p class="text-[#3882B6] font-semibold text-sm mb-3">${localidad}</p>

        <p class="text-gray-600 text-sm line-clamp-3 mb-4">${descripcion}</p>
      </div>

      <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <a href="${escapeHtml(enlace)}" target="_blank" class="text-[#3882B6] font-bold text-sm hover:text-blue-800 transition-colors">
          Ver detalles →
        </a>
      </div>
    `;
    grid.appendChild(card);
  }
}

function renderPager() {
  pager.classList.toggle("hidden", state.totalPages <= 1);

  const isPrevDisabled = state.page <= 1;
  const isNextDisabled = state.page >= state.totalPages;

  prevBtn.disabled = isPrevDisabled;
  nextBtn.disabled = isNextDisabled;

  prevBtn.classList.toggle("opacity-40", isPrevDisabled);
  nextBtn.classList.toggle("opacity-40", isNextDisabled);

  pageSelect.innerHTML = "";
  for (let i = 1; i <= state.totalPages; i++) {
    const opt = document.createElement("option");
    opt.value = String(i);
    opt.textContent = String(i);
    if (i === state.page) opt.selected = true;
    pageSelect.appendChild(opt);
  }

  pagesText.textContent = `de ${state.totalPages}`;

  if (state.total > 0) {
    const start = (state.page - 1) * state.pageSize + 1;
    const end = Math.min(state.page * state.pageSize, state.total);
    rangeText.textContent = `Mostrando del ${start} al ${end}`;
  } else {
    rangeText.textContent = "";
  }
}

function syncUrl(qs) {
  const url = new URL(window.location.href);
  url.search = qs;
  history.replaceState(null, "", url.toString());
}

async function fetchResults(page = 1) {
  state.page = page;

  const qs = buildQuery(page);
  statusText.textContent = "Cargando...";

  const res = await fetch(`../ASSETS/API/empleo.php?${qs}`, {
    headers: { "Accept": "application/json" }
  });

  if (!res.ok) {
    throw new Error("Error en la API de empleo");
  }

  const data = await res.json();

  state.total = Number(data.total || 0);
  state.page = Number(data.page || page);
  state.pageSize = Number(data.pageSize || state.pageSize);
  state.totalPages = Number(data.totalPages || 1);

  totalText.textContent = `Explora ${state.total} vacantes activas con estos filtros.`;
  statusText.textContent = "";

  renderCards(data.items || []);
  renderPager();
  syncUrl(qs);
}

const fetchDebounced = debounce(() => {
  fetchResults(1).catch((e) => {
    statusText.textContent = "Error cargando resultados.";
    console.error(e);
  });
}, 300);

// Listeners (live)
fTitulo.addEventListener("input", fetchDebounced);
fDias.addEventListener("change", fetchDebounced);

// Provincia: al cambiar, tu provincias.js cargará localidades; nosotros reseteamos la localidad y buscamos.
selectProvincia.addEventListener("change", () => {
  // Reseteo UI de localidad (por si el otro JS tarda en cargar)
  if (selectLocalidad) {
    selectLocalidad.value = "";
  }
  fetchDebounced();
});

// Localidad: al elegir, buscamos
selectLocalidad.addEventListener("change", fetchDebounced);

// Paginación
prevBtn.addEventListener("click", () => {
  if (state.page > 1) {
    fetchResults(state.page - 1).catch(console.error);
  }
});

nextBtn.addEventListener("click", () => {
  if (state.page < state.totalPages) {
    fetchResults(state.page + 1).catch(console.error);
  }
});

pageSelect.addEventListener("change", () => {
  const p = parseInt(pageSelect.value, 10);
  if (!Number.isNaN(p)) {
    fetchResults(p).catch(console.error);
  }
});

// Reset
btnReset.addEventListener("click", (e) => {
  e.preventDefault();

  fTitulo.value = "";
  fDias.value = "0";

  // Provincia vuelve a "todas"
  selectProvincia.value = "";

  // Localidad deshabilitada (coherente con tu provincias.js)
  if (selectLocalidad) {
    selectLocalidad.innerHTML = '<option value="">Selecciona primero una provincia</option>';
    selectLocalidad.disabled = true;
  }

  fetchResults(1).catch(console.error);
});

// Carga inicial
fetchResults(1).catch(console.error);


})();