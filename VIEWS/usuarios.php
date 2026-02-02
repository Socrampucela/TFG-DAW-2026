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
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                principal: {
                  100: '#dbeafe',
                  500: '#3b82f6',
                  600: '#2563eb',
                  700: '#1d4ed8',
                }
              }
            }
          }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="max-w-6xl mx-auto my-10 bg-white shadow-xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
    
    <?php 
        $pagina_actual = 'usuarios'; 
        include_once "../includes/sideNavAdmin.php"; 
    ?>

    <main class="flex-1 p-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
                <p class="text-gray-400 text-sm mt-1">Total: <?= count($usuarios) ?> registros</p>
            </div>
            <a href="register.php" class="bg-principal-600 hover:bg-principal-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow-md">
                + Nuevo Usuario
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-gray-400">
                        <th class="px-4 py-2 text-xs uppercase tracking-wider font-semibold">ID</th>
                        <th class="px-4 py-2 text-xs uppercase tracking-wider font-semibold">Usuario</th>
                        <th class="px-4 py-2 text-xs uppercase tracking-wider font-semibold">Rol</th>
                        <th class="px-4 py-2 text-xs uppercase tracking-wider font-semibold">Registro</th>
                        <th class="px-4 py-2 text-xs uppercase tracking-wider font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php 
                    foreach ($usuarios as $usuario) {
                        $badgeStyle = ($usuario['rol'] === 'admin') ? 'text-purple-600 bg-purple-50' : 'text-blue-600 bg-blue-50';
                        $fecha = date('d/m/Y', strtotime($usuario['fecha_registro']));
                        $nombre = htmlspecialchars($usuario['nombre_apellido']);

                        echo "<tr class='hover:bg-gray-50 transition-all'>";
                        echo "<td class='px-4 py-4 text-sm font-mono text-gray-300'>#$usuario[id]</td>"; 
                        echo "<td class='px-4 py-4 text-sm font-medium text-gray-700'>$nombre</td>"; 
                        echo "<td class='px-4 py-4'><span class='px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide $badgeStyle'>" . $usuario['rol'] . "</span></td>"; 
                        echo "<td class='px-4 py-4 text-sm text-gray-400'>$fecha</td>"; 
                        echo "<td class='px-4 py-4 text-right text-sm font-bold space-x-4'>
                                <a href='editarUsuario.php?id=$usuario[id]' class='text-gray-400 hover:text-principal-600 transition-colors'>Editar</a>
                                <a href='../ASSETS/php/borrarUsuario.php?id=$usuario[id]' 
                                   onclick=\"return confirm('¿Eliminar a $nombre?')\"
                                   class='text-gray-400 hover:text-red-500 transition-colors'>
                                   Borrar
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>