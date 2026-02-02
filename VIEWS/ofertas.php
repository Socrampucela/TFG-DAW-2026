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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --c-primary: #2B2F33; --c-secondary: #3882B6; --c-accent: #F59E0B;
            --c-bg: #FAFAFA; --radius: 14px; --border: 1px solid rgba(43,47,51,.14);
            --shadow: 0 18px 45px rgba(0,0,0,.10); --muted: rgba(43,47,51,.70);
        }
        .panel-table { background: #fff; border: var(--border); border-radius: var(--radius); box-shadow: var(--shadow); width: 100%; max-width: 1200px; }
        
        /* Botón con tu color secundario azul */
        .btn-custom { background: var(--c-secondary); color: #fff; font-weight: 700; padding: 6px 12px; border-radius: 8px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn-custom:hover { filter: brightness(1.1); }

        /* Estilo para el encabezado de la tabla (Quitamos el gris oscuro/azul de Tailwind) */
        .table-header { background-color: #f8fafc; color: var(--muted); text-transform: uppercase; font-size: 10px; font-weight: 700; letter-spacing: 0.05em; }
        
        .col-titulo { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 700; color: var(--c-primary); }
        .col-desc { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--muted); }
        
        .form-input-custom { border: var(--border); border-radius: 8px; padding: 6px 12px; outline: none; transition: border-color 0.2s; }
        .form-input-custom:focus { border-color: var(--c-secondary); }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[var(--c-primary)] text-xs antialiased">
<?php include_once "../includes/header.php"; ?>
<div class="flex min-h-screen p-4 gap-6 justify-center">
    <?php $pagina_actual = 'ofertas'; include_once "../includes/sideNavAdmin.php"; ?>

    <main class="panel-table h-fit flex flex-col overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold tracking-tight" style="color: var(--c-primary)">Gestión de Ofertas</h1>
                <p style="color: var(--muted)">Total: <?= $total ?> vacantes encontradas</p>
            </div>
            <form class="flex gap-2">
                <input type="text" name="titulo" value="<?= $filtros['titulo'] ?>" placeholder="Buscar puesto..." class="form-input-custom text-xs">
                <button type="submit" class="btn-custom text-xs">Filtrar</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="table-header">
                        <th class="p-4 border-b">Título</th>
                        <th class="p-4 border-b">Ubicación</th>
                        <th class="p-4 border-b">Fecha</th>
                        <th class="p-4 border-b">Descripción</th>
                        <th class="p-4 border-b text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($empleos as $e): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 col-titulo"><?= $e['Título'] ?></td>
                        <td class="p-4 font-semibold text-gray-700">
                            <?= $e['Provincia'] ?> 
                            <div class="text-[10px] font-normal text-gray-400"><?= $e['Localidad'] ?></div>
                        </td>
                        <td class="p-4 text-[var(--muted)]"><?= $e['Fecha publicación'] ?></td>
                        <td class="p-4 col-desc"><?= $e['Descripción'] ?></td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="<?= $e['Enlace al contenido'] ?>" target="_blank" class="hover:opacity-70 transition-opacity" title="Ver" style="color: var(--c-secondary)">🔗</a>
                                <a href="modificarOferta.php?id=<?= $e['Identificador'] ?>" class="hover:opacity-70 transition-opacity" title="Editar" style="color: var(--c-primary)">✏️</a>
                                <a href="../ASSETS/php/borrarOferta.php?id=<?= $e['Identificador'] ?>" onclick="return confirm('¿Eliminar?')" class="hover:opacity-70 transition-opacity text-red-500" title="Borrar">🗑️</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-gray-50/50 border-t flex justify-between items-center">
            <span class="font-medium" style="color: var(--muted)">Página <?= $pag ?> de <?= $paginas ?></span>
            <div class="flex gap-1 text-[11px]">
                <?php if ($pag > 1): ?> 
                    <a href="?pag=<?= $pag-1 ?>&<?= $urlParams ?>" class="px-3 py-1 bg-white border rounded-lg font-bold hover:bg-gray-100">«</a> 
                <?php endif; ?>
                
                <span class="btn-custom flex items-center px-4"><?= $pag ?></span>
                
                <?php if ($pag < $paginas): ?> 
                    <a href="?pag=<?= $pag+1 ?>&<?= $urlParams ?>" class="px-3 py-1 bg-white border rounded-lg font-bold hover:bg-gray-100">»</a> 
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?php  include '../INCLUDES/footer.php';

?>
</body>
</html>