<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: index.php?error=login_required");
    exit();
}

require_once "../CONFIG/db.php"; 
include "../INCLUDES/header.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Empleo</title>
    <style>
        @media (max-width: 640px) {
            main { padding: 1rem !important; }
            .panel { max-width: 100% !important; margin: 0 !important; }
            .page-title { font-size: 1.5rem !important; }
        }
    </style>
</head>
<body class="bg-[var(--c-bg)]">

<main class="flex-grow flex justify-center items-start py-10 px-4">
    <section class="panel" style="width:100%; max-width:520px;">
        <div class="panel__inner">
            <h1 class="page-title">Crear una oferta de empleo</h1>
            <p class="page-subtitle">Completa los campos para publicar la vacante.</p>
            <div id="divErrores"></div>
            
            <form class="form" action="../ASSETS/PHP/procesarEmpleo.php" method="post" id="formulario" novalidate>
                
                <div>
                    <label class="form-label" for="titulo">Título de la oferta:</label>
                    <input class="form-input" type="text" id="titulo" name="titulo" required>
                </div>

                <div>
                    <label class="form-label" for="select-provincia">Provincia:</label>
                    <select class="form-input" id="select-provincia" name="provincia">
                        <option value="">Selecciona una provincia</option>
                        <?php 
                        if(isset($conn)){
                            $sentencia = $conn->query("SELECT DISTINCT Cod_Provincia, Provincia FROM municipiosjcyl ORDER BY Provincia ASC");
                            while ($r = $sentencia->fetch()) {
                                echo '<option value="' . $r['Cod_Provincia'] . '">' . $r['Provincia'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" for="select-localidad">Localidad:</label>
                    <select class="form-input" id="select-localidad" name="localidad" disabled>
                        <option value="">Selecciona primero una provincia</option>
                    </select>
                </div>

                <div>
                     <label class="form-label" for="descripcion">Descripción:</label>
                     <textarea class="form-input" id="descripcion" name="descripcion" rows="4"></textarea>
                </div>

                <div>
                    <label class="form-label" for="enlace">Enlace al empleo (Junta de CyL):</label>
                    <input class="form-input" type="url" name="enlace" id="enlace" placeholder="https://...">
                </div>

                <button class="btn-primary" type="submit" style="margin-top: 1.5rem;">Publicar Oferta</button>
            </form>

            <script src="../ASSETS/JS/provincias.js"></script>
        </div>
    </section>
</main>

<?php include "../INCLUDES/footer.php"; ?>    
</body>
</html>