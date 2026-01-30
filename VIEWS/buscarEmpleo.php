<?php
require_once("../config/db.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar ofertas</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style type="text/tailwindcss">
        @layer components {
            .job-card { @apply bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between; }
            .input { @apply w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200; }
            .select { @apply w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200; }
        }
    </style>
</head>
<body class="bg-gray-50">
<?php include("../includes/header.php"); ?>

<main class="max-w-6xl mx-auto py-12 px-6">
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Buscar ofertas</h1>
        <p class="text-gray-500 mt-2 text-lg" id="totalText">Escribe o filtra para ver resultados.</p>
    </div>

    <!-- Filtros -->
    <section class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Título</label>
                <input id="fTitulo" class="input" type="text" placeholder="Ej. Cocinero, Albañil..." autocomplete="off">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Provincia</label>
                <select id="select-provincia" class="select">
                    <option value="">Todas</option>
                    <?php
                        if(isset($conn)){
                            $sentencia = $conn->query("SELECT DISTINCT Cod_Provincia, Provincia FROM municipiosjcyl ORDER BY Provincia ASC");
                            while ($r = $sentencia->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . htmlspecialchars($r['Cod_Provincia']) . '">' . htmlspecialchars($r['Provincia']) . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Localidad</label>
                <select id="select-localidad" class="select" disabled>
                    <option value="">Selecciona primero una provincia</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha</label>
                <select id="fDias" class="select">
                    <option value="0">Cualquiera</option>
                    <option value="7">Últimos 7 días</option>
                    <option value="30">Últimos 30 días</option>
                    <option value="90">Últimos 90 días</option>
                </select>
            </div>

        </div>

        <div class="mt-4 flex items-center justify-between gap-4">
            <p class="text-sm text-gray-400" id="statusText"></p>
            <button id="btnReset" class="text-sm font-bold text-[#3882B6] hover:underline">Limpiar filtros</button>
        </div>
    </section>

    <!-- Resultados -->
    <div id="grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

    <!-- Paginación (misma estética que tu index) -->
    <nav id="pager" class="mt-16 flex flex-col items-center gap-4 hidden">
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

            <button id="prevBtn" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" aria-label="Anterior">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 font-medium">Página</span>
                <select id="pageSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 font-bold outline-none"></select>
                <span class="text-sm text-gray-500 font-medium" id="pagesText"></span>
            </div>

            <button id="nextBtn" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" aria-label="Siguiente">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

        </div>

        <p class="text-xs text-gray-400 italic" id="rangeText"></p>
    </nav>
</main>


<script src="../ASSETS/js/provincias.js"></script>
<script src="../ASSETS/js/buscarEmpleo.js"></script>
<?php  include '../INCLUDES/footer.php'; ?>
</body>
</html>
