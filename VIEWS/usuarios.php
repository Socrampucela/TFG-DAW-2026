<?php 
include_once "../DAO/usuarioDAO.php";
include_once "../config/db.php";
session_start(); 

$usuarioDAO = new UsuarioDAO($conn);
$usuarios = $usuarioDAO->mostrarTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto my-10 bg-white shadow-2xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
    
    <aside class="w-full md:w-64 bg-gray-50 border-r border-gray-200 p-6 flex flex-col gap-2">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menú Principal</h2>
        
        <button onclick="location.href='dashboard.php'" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-all font-medium">
            <span class="text-xl">📊</span> Panel de Control
        </button>
        
        <button class="flex items-center gap-3 px-4 py-2 text-principal-600 bg-white border border-principal-100 rounded-lg shadow-sm font-medium">
            <span class="text-xl">👥</span> Gestionar Usuarios
        </button>
        
        <button onclick="location.href='ofertas.php'" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-all font-medium">
            <span class="text-xl">💼</span> Gestionar Ofertas
        </button>

        <div class="mt-auto pt-6 border-t border-gray-200">
            <a href="../AUTH/logout.php" class="text-sm text-red-500 hover:underline font-semibold">Cerrar Sesión</a>
        </div>
    </aside>

    <main class="flex-1 p-8 bg-white">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 border-b-4 border-principal-500 inline-block pb-1">Gestión de Usuarios</h1>
                <p class="text-gray-500 mt-2 text-sm">Mostrando <?= count($usuarios) ?> usuarios registrados.</p>
            </div>
            <button class="bg-principal-600 hover:bg-principal-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                + Nuevo Usuario
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Usuario</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Rol</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Registro</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-gray-400">#<?= $usuario['id'] ?></td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($usuario['nombre_apellido']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $usuario['rol'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-principal-100 text-principal-800' ?>">
                                <?= ucfirst($usuario['rol']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="editar.php?id=<?= $usuario['id'] ?>" class="text-principal-600 hover:text-principal-900 text-sm font-bold">Editar</a>
                            <a href="eliminar.php?id=<?= $usuario['id'] ?>" 
                               onclick="return confirm('¿Eliminar permanentemente a <?= htmlspecialchars($usuario['nombre_apellido']) ?>?')"
                               class="text-red-500 hover:text-red-700 text-sm font-bold">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>