<?php
// 1. Subimos solo un nivel para entrar en DAO o config
include_once "../DAO/usuarioDAO.php";
include_once "../config/db.php";
session_start();

// 2. Si ya estás en VIEWS, login.php está en la misma carpeta
if (!isset($_SESSION['nombre'])) { header("Location: login.php"); exit; }

$dao = new UsuarioDAO($conn);
$id = $_GET['id'] ?? null;

// 3. Redirección a la lista (misma carpeta)
if (!$id) { header("Location: usuarios.php"); exit; }

// Asegúrate de que el nombre del método en el DAO sea este:
$user = $dao->obtenerPorId($id); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datosActualizar = [
        'nombre_apellido' => $_POST['nombre'],
        'email'           => $_POST['email'],
        'rol'             => $_POST['rol']
    ];

    
    if ($dao->actualizar((int)$id, $datosActualizar)) {
        header("Location: usuarios.php?msg=updated");
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
    @layer components {
        .grid-2 { 
            @apply grid grid-cols-1 md:grid-cols-2 gap-4; 
        }
        .panel { 
            @apply w-full max-w-[520px] mx-auto; 
        }
    }
    @media (max-width: 640px) {
        .page-center { @apply p-4 flex flex-col items-center justify-start !important; }
        .page-title { @apply text-2xl !important; }
        .btn-primary { @apply w-full justify-center !important; }
    }
</style>
</head>
<body class="page-center">

    <div class="panel">
        <div class="panel__inner">
            <header>
                <h1 class="page-title">Editar Usuario</h1>
                <p class="page-subtitle">Modificando a: <b><?= $user['nombre_apellido'] ?></b></p>
            </header>

            <form method="POST" class="form">
                <div class="grid-2">
                    <div>
                        <label class="form-label">Nombre y Apellidos</label>
                        <input type="text" name="nombre" class="form-input" value="<?= $user['nombre_apellido'] ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="<?= $user['email'] ?>" required>
                    </div>
                </div>


                <div>
                    <label class="form-label">Rol del Sistema</label>
                    <select name="rol" class="form-input">
                        <option value="administrador" <?= $user['rol'] == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                        <option value="usuario" <?= $user['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                    </select>
                </div>

                <div class="divider"></div>

                <div class="grid-2">
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                    <a href="usuarios.php" class="btn-primary" 
                       style="background: var(--c-surface); color: var(--c-primary); text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; box-shadow: none;">
                       Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>