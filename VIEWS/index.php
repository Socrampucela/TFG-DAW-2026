<?php
require_once("../config/db.php");
require_once("../DAO/EmpleoDAO.php");
require_once("../CLASSES/empleo.php");
require_once('../INCLUDES/funciones-comunes.php');

$gestionEmpleos = new EmpleoDAO($conn);

$empleosPorPagina = 20;
$paginaActual = max(1, (int)($_GET['pagina'] ?? 1));
$inicio = ($paginaActual - 1) * $empleosPorPagina;

$empleos = $gestionEmpleos->obtenerEmpleosPaginado($inicio, $empleosPorPagina);
$totalEmpleos = $gestionEmpleos->contarTodos();
$totalPaginas = ceil($totalEmpleos / $empleosPorPagina);

$mostrarAlerta = isset($_GET["error"]) && $_GET["error"] === "login_required";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php renderizarHead('Portal de empleos - Empleo360'); ?>
    <?php renderizarEstilosTailwind(); ?>
</head>
<body class="
  bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]
">

    <?php include("../includes/header.php"); ?>
    
    <main class="max-w-6xl mx-auto py-12 px-6">
        <?php if ($mostrarAlerta): ?>
            <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700">
                Debe iniciar sesión para acceder a esta zona
            </div>
        <?php endif; ?>

        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Ofertas de empleo</h1>
            <p class="text-gray-500 mt-2 text-lg">Explora <?= $totalEmpleos ?> vacantes activas en este momento.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($empleos as $empleo): ?>
                <article class="job-card min-h-[250px]">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#3882B6] bg-blue-50 px-2 py-1 rounded">
                                <?= $empleo['Categoría'] ?? 'General' ?>
                            </span>
                            <span class="text-gray-400 text-xs">
                                <?= formatearFecha($empleo["Fecha publicación"], 'd M') ?>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">
                            <?= $empleo['Título'] ?>
                        </h2>
                        <p class="text-[#3882B6] font-semibold text-sm mb-3">
                            <?= $empleo['Localidad'] ?>
                        </p>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            <?= truncar($empleo['Descripción'], 150) ?>
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="<?= $empleo['Enlace al contenido'] ?>" target="_blank" 
                           class="text-[#3882B6] font-bold text-sm hover:text-blue-800 transition-colors">
                            Ver detalles →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php renderizarPaginacion($paginaActual, $totalPaginas, '', ['inicio' => $inicio, 'empleosPorPagina' => $empleosPorPagina, 'totalEmpleos' => $totalEmpleos]); ?>
        
        <?php if ($totalPaginas > 1): ?>
            <p class="text-xs text-gray-400 italic text-center mt-4">
                Mostrando del <?= $inicio + 1 ?> al <?= min($inicio + $empleosPorPagina, $totalEmpleos) ?>
            </p>
        <?php endif; ?>
    </main>
    
    <?php include '../INCLUDES/footer.php'; ?>
</body>
</html>