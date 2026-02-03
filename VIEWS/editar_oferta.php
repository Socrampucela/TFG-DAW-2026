<?php
include_once "../DAO/empleoDAO.PHP";
include_once "../config/db.php";
require_once '../INCLUDES/funciones-comunes.php';
session_start();

$dao = new EmpleoDAO($conn);
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ofertas.php");
    exit;
}

$oferta = $dao->obtenerPorId($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convertir código de provincia a nombre
    $nombreProvincia = obtenerNombreProvincia($conn, $_POST['provincia']);
    
    $datos = [
        ':titulo'      => $_POST['titulo'],
        ':provincia'   => $nombreProvincia,
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
    <?php renderizarHead('Modificar Oferta - Empleo360'); ?>
</head>
<body class="
  bg-[linear-gradient(135deg,#F5F4F0_0%,#F2F2EE_40%,#EDECE8_100%)]
">

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
                        <label class="form-label" for="select-provincia">Provincia:</label>
                        <?php 
                        renderizarSelectProvincias(
                            $conn, 
                            'provincia', 
                            'select-provincia', 
                            true, 
                            $oferta['Provincia'], 
                            true
                        ); 
                        ?>
                    </div>
                    <div>
                        <label class="form-label" for="select-localidad">Localidad:</label>
                        <select class="form-input" id="select-localidad" name="localidad" required>
                            <option value="">Selecciona primero una provincia</option>
                            <?php 
                            if (!empty($oferta['Localidad'])) {
                                echo '<option value="' . $oferta['Localidad'] . '" selected>' . $oferta['Localidad'] . '</option>';
                            }
                            ?>
                        </select>
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
                    <a href="ofertas.php" class="btn-primary" style="background: var(--c-surface); color: var(--c-primary);">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../ASSETS/js/provincias.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinciaSelect = document.getElementById('select-provincia');
            const localidadActual = '<?= $oferta['Localidad'] ?? '' ?>';
            
            if (provinciaSelect.value && localidadActual) {
                provinciaSelect.dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    const localidadSelect = document.getElementById('select-localidad');
                    Array.from(localidadSelect.options).forEach(option => {
                        if (option.value === localidadActual) {
                            option.selected = true;
                        }
                    });
                }, 500);
            }
        });
    </script>
</body>
</html>