<?php
include_once "../DAO/empleoDAO.php";
include_once "../config/db.php";
session_start();

$dao = new EmpleoDAO($conn);
$id = $_GET['id'] ?? null;

if (!$id) { header("Location: ofertas.php"); exit; }

$oferta = $dao->obtenerPorId($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        ':titulo'      => $_POST['titulo'],
        ':provincia'   => $_POST['provincia'],
        ':localidad'   => $_POST['localidad'],
        ':descripcion' => $_POST['descripcion'],
        ':enlace'      => $_POST['enlace']
    ];
    
    if ($dao->actualizar($id, $datos)) {
        header("Location: ofertas.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Oferta</title>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="page-center">

    <div class="panel">
        <div class="panel__inner">
            <header>
                <h1 class="page-title">Editar Oferta</h1>
                <p class="page-subtitle">Actualiza la información de la vacante directamente.</p>
            </header>

            <form method="POST" class="form">
                <div>
                    <label class="form-label">Título del puesto</label>
                    <input type="text" name="titulo" class="form-input" value="<?= $oferta['Título'] ?>" required>
                </div>

                <div class="grid-2">
                    <div>
                        <label class="form-label">Provincia</label>
                        <input type="text" name="provincia" class="form-input" value="<?= $oferta['Provincia'] ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Localidad</label>
                        <input type="text" name="localidad" class="form-input" value="<?= $oferta['Localidad'] ?>" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-input" rows="5" style="resize:none;"><?= $oferta['Descripción'] ?></textarea>
                </div>

                <div>
                    <label class="form-label">URL del contenido</label>
                    <input type="url" name="enlace" class="form-input" value="<?= $oferta['Enlace al contenido'] ?>" required>
                </div>

                <div class="divider"></div>

                <div class="grid-2">
                    <button type="submit" class="btn-primary">Guardar Cambios</button>
                    <a href="ofertas.php" class="btn-primary" style="background: var(--c-surface); color: var(--c-primary); text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>