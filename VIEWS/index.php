<?php

require_once("../config/db.php");
require_once("../DAO/EmpleoDAO.php");
require_once ("../CLASSES/empleo.php");

$gestionEmpleos = new EmpleoDAO($conn);

$empleosPorPagina = 20;
$paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $empleosPorPagina;

$empleos = $gestionEmpleos->obtenerEmpleosPaginado($inicio, $empleosPorPagina);
$totalEmpleos = $gestionEmpleos->contarTodos();
$totalPaginas = ceil($totalEmpleos / $empleosPorPagina);
if(isset($_GET["error"])){
if($_GET["error"]=="login_required"){
    echo "<div class='mb-4 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 flex items-center gap-3'>";
    echo "<p> Debe iniciar sesión para acceder a esta zona</p>";
    echo "</div>";
}}else{

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de empleos</title>
    <script src="https://cdn.tailwindcss.com"></script>
   <style type="text/tailwindcss">
        @layer components {
            .job-card { @apply bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php include("../includes/header.php"); ?>
    <main class="max-w-6xl mx-auto py-12 px-6">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Ofertas de empleo</h1>
            <p class="text-gray-500 mt-2 text-lg">Explora <?php echo $totalEmpleos; ?> vacantes activas en este momento.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($empleos as $empleo): ?>
                <article class="job-card min-h-[250px]">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#3882B6] bg-blue-50 px-2 py-1 rounded">
                                <?php echo htmlspecialchars($empleo['Categoría'] ?? 'General'); ?>
                            </span>
                            <span class="text-gray-400 text-xs">
                                <?php echo date('d M', strtotime($empleo["Fecha publicación"])); ?>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
                            <?php echo htmlspecialchars($empleo['Título']); ?>
                        </h2>
                        <p class="text-[#3882B6] font-semibold text-sm mb-3">
                            <?php echo htmlspecialchars($empleo['Localidad']); ?>
                        </p>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            <?php 
                                $descripcion = $empleo['Descripción'];
                                echo htmlspecialchars(strip_tags($descripcion));
                            ?>
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="<?php echo $empleo['Enlace al contenido']; ?>" target="_blank" class="text-[#3882B6] font-bold text-sm hover:text-blue-800 transition-colors">
                            Ver detalles →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPaginas > 1): ?>
            <nav class="mt-16 flex flex-col items-center gap-4">
                <div class="flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    
                    <?php if ($paginaActual > 1): ?>
                        <a href="?pagina=<?php echo $paginaActual - 1; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 font-medium">Página</span>
                        <select onchange="window.location.href='?pagina=' + this.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 font-bold outline-none">
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($i === $paginaActual) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <span class="text-sm text-gray-500 font-medium">de <?php echo $totalPaginas; ?></span>
                    </div>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="?pagina=<?php echo $paginaActual + 1; ?>" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php endif; ?>

                </div>
                <p class="text-xs text-gray-400 italic">
                    Mostrando del <?php echo $inicio + 1; ?> al <?php echo min($inicio + $empleosPorPagina, $totalEmpleos); ?>
                </p>
            </nav>
        <?php endif; ?>
    </main>
</body>
</html>