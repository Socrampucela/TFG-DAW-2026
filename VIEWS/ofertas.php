<?php
include_once "../DAO/empleoDAO.php"; include_once "../config/db.php";
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
    <meta charset="UTF-8"><title>Ofertas - Admin</title>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        .panel-wide { max-width: 1200px !important; width: 100%; }
        .table-admin { @apply w-full text-left border-collapse text-[13px]; }
        .table-admin th { @apply p-4 border-b bg-gray-50 text-[10px] uppercase font-bold tracking-wider text-gray-500; }
        .table-admin td { @apply p-4 border-b border-gray-50; }
        .col-titulo { @apply font-bold text-[var(--c-primary)] max-w-[200px] truncate; }
        .col-desc { @apply text-gray-400 italic text-xs max-w-[300px] truncate; }
        
        .btn-pagination { @apply !w-auto !py-1 !px-3 !text-xs !shadow-none; }
    </style>
</head>
<body class="bg-[var(--c-bg)] text-[var(--c-primary)] antialiased">

<?php include_once "../includes/header.php"; ?>

<div class="flex min-h-screen p-4 md:p-8 gap-6 justify-center">
    <div class="hidden lg:block">
        <?php $pagina_actual = 'ofertas'; include_once "../includes/sideNavAdmin.php"; ?>
    </div>

    <main class="panel panel-wide h-fit flex flex-col overflow-hidden">
        <div class="panel__inner border-b flex justify-between items-center bg-white">
            <div>
                <h1 class="page-title !mb-1">Gestión de Ofertas</h1>
                <p class="page-subtitle !mb-0 text-xs">Total: <?= $total ?> vacantes</p>
            </div>
            <form class="flex gap-2">
                <input type="text" name="titulo" value="<?= htmlspecialchars($filtros['titulo']) ?>" 
                       placeholder="Buscar puesto..." class="form-input !py-2 !text-xs w-48">
                <button type="submit" class="btn-primary !w-auto !py-2 !px-4 !text-xs !shadow-none">Filtrar</button>
            </form>
        </div>

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
                        <td class="col-titulo"><?= htmlspecialchars($e['Título']) ?></td>
                        <td>
                            <div class="font-semibold"><?= htmlspecialchars($e['Provincia']) ?></div>
                            <div class="text-[10px] text-gray-400"><?= htmlspecialchars($e['Localidad']) ?></div>
                        </td>
                        <td class="text-gray-500 whitespace-nowrap"><?= $e['Fecha publicación'] ?></td>
                        <td class="col-desc"><?= strip_tags($e['Descripción']) ?></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-3 text-base">
                                <a href="<?= $e['Enlace al contenido'] ?>" target="_blank" title="Ver" class="hover:scale-110 transition-transform">🔗</a>
                                <a href="editar_oferta.php?id=<?= $e['Identificador'] ?>" title="Editar" class="hover:scale-110 transition-transform">✏️</a>
                                <a href="../ASSETS/php/borrarOferta.php?id=<?= $e['Identificador'] ?>" 
                                   onclick="return confirm('¿Eliminar?')" title="Borrar" class="hover:scale-110 transition-transform">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="panel__inner bg-gray-50/50 flex justify-between items-center border-t">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Pág. <?= $pag ?> / <?= $paginas ?></span>
            <div class="flex gap-1">
                <?php if ($pag > 1): ?> 
                    <a href="?pag=<?= $pag-1 ?>&<?= $urlParams ?>" class="btn-primary btn-pagination !bg-white !text-gray-700 !border-gray-200">«</a> 
                <?php endif; ?>
                
                <span class="btn-primary btn-pagination cursor-default"><?= $pag ?></span>
                
                <?php if ($pag < $paginas): ?> 
                    <a href="?pag=<?= $pag+1 ?>&<?= $urlParams ?>" class="btn-primary btn-pagination !bg-white !text-gray-700 !border-gray-200">»</a> 
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include '../INCLUDES/footer.php'; ?>
</body>
</html>