<?php
include_once "../DAO/empleoDAO.php"; 
include_once "../config/db.php";
session_start();


$dao = new EmpleoDAO($conn);
$pag = max(1, (int)($_GET['pag'] ?? 1));
$filtros = ['titulo' => $_GET['titulo'] ?? '', 'provincia' => $_GET['provincia'] ?? ''];
$limit = 20;
$empleos = $dao->buscarEmpleosPaginado($filtros, ($pag - 1) * $limit, $limit);
$total = $dao->contarFiltrados($filtros);
$paginas = ceil($total / $limit);
$urlParams = http_build_query($filtros);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas - Admin</title>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        /* Mantenemos tus clases de tabla pero integradas en el layout nuevo */
        .table-admin { @apply w-full text-left border-collapse text-[13px]; }
        .table-admin th { @apply p-4 border-b bg-gray-50 text-[10px] uppercase font-bold tracking-wider text-gray-500; }
        .table-admin td { @apply p-4 border-b border-gray-50; }
        .col-titulo { @apply font-bold text-[var(--c-primary)] max-w-[200px] truncate; }
        .col-desc { @apply text-gray-400 italic text-xs max-w-[300px] truncate; }
        .btn-pagination { @apply !w-auto !py-1 !px-3 !text-xs !shadow-none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<?php include_once "../includes/header.php"; ?>

<div class="max-w-6xl mx-auto my-10 bg-white shadow-xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
    
    <?php 
        $pagina_actual = 'ofertas'; 
        include_once "../includes/sideNavAdmin.php"; 
    ?>

    <main class="flex-1 p-8 flex flex-col overflow-hidden">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Ofertas</h1>
                <p class="text-gray-400 text-sm mt-1">Total: <?= $total ?> vacantes publicadas</p>
            </div>
            
            <form class="flex gap-2">
                <input type="text" name="titulo" value="<?= ($filtros['titulo']) ?>" 
                       placeholder="Buscar puesto..." 
                       class="bg-white border border-gray-200 text-gray-600 text-sm py-2 px-4 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all w-48">
                <button type="submit" class="btn-primary !w-auto !py-2 !px-4 !text-xs !shadow-none">Filtrar</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($empleos as $e): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="col-titulo"><?= ($e['Título']) ?></td>
                            <td>
                                <div class="font-semibold"><?= ($e['Provincia']) ?></div>
                                <div class="text-[10px] text-gray-400"><?= ($e['Localidad']) ?></div>
                            </td>
                            <td class="text-gray-500 whitespace-nowrap"><?= $e['Fecha publicación'] ?></td>
                            <td class="col-desc"><?= strip_tags($e['Descripción']) ?></td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-3 text-base">
                                    <a href="<?= $e['Enlace al contenido'] ?>" target="_blank" title="Ver" class="hover:scale-110 transition-transform">🔗</a>
                                    <a href="editar_oferta.php?id=<?= $e['Identificador'] ?>" title="Editar" class="hover:scale-110 transition-transform">✏️</a>
                                    <a href="../ASSETS/php/borrarOferta.php?id=<?= $e['Identificador'] ?>" 
                                       onclick="return confirm('¿Eliminar?')" title="Borrar" class="hover:scale-110 transition-transform text-red-400">🗑️</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50/50 flex justify-between items-center border-t border-gray-100">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pág. <?= $pag ?> / <?= $paginas ?></span>
                <div class="flex gap-1">
                    <?php if ($pag > 1): ?> 
                        <a href="?pag=<?= $pag-1 ?>&<?= $urlParams ?>" class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-lg hover:bg-gray-100 transition-all text-xs">«</a> 
                    <?php endif; ?>
                    
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-bold"><?= $pag ?></span>
                    
                    <?php if ($pag < $paginas): ?> 
                        <a href="?pag=<?= $pag+1 ?>&<?= $urlParams ?>" class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-lg hover:bg-gray-100 transition-all text-xs">»</a> 
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../INCLUDES/footer.php'; ?>
</body>
</html>