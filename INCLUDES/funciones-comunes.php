<?php
/**
 * Funciones comunes para Empleo360
 */

/**
 * Renderiza el head común para todas las páginas
 */
function renderizarHead($titulo = 'Empleo360', $cssAdicional = []) {
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../ASSETS/css/components.css">
    <link rel="stylesheet" href="../ASSETS/css/estilos-comunes.css">
    <?php foreach ($cssAdicional as $css): ?>
        <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
    <?php
}

/**
 * Renderiza los estilos Tailwind comunes
 */
function renderizarEstilosTailwind() {
    ?>
    <style type="text/tailwindcss">
    @layer components {
        .job-card { @apply bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 flex flex-col justify-between; }
        .input-field { @apply w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-200; }
    }
    </style>
    <?php
}

/**
 * Renderiza mensaje de alerta
 */
function renderizarAlerta($mensaje, $tipo = 'Error') {
    if (!$mensaje) return;
    $clases = [
        'Exito' => 'alert-Exito',
        'Error' => 'alert-Error',
        'Advertencia' => 'alert-Advertencia'
    ];
    $clase = $clases[$tipo] ?? 'alert-Error';
    ?>
    <div class="alert <?= $clase ?>">
        <strong><?= $tipo ?>:</strong> <?= $mensaje ?>
    </div>
    <?php
}

/**
 * Obtiene mensaje según parámetro GET
 */
function obtenerMensajeDeGet() {
    $mensaje = '';
    $tipo = '';
    
    if (isset($_GET['registro'])) {
        switch ($_GET['registro']) {
            case 'exito':
                $mensaje = 'Cuenta creada exitosamente. Ya puedes iniciar sesión.';
                $tipo = 'Exito';
                break;
            case 'error':
                $mensaje = 'Error al crear la cuenta. Inténtalo de nuevo.';
                $tipo = 'Error';
                break;
        }
    }
    
    if (isset($_GET['error'])) {
        $errorMsg = $_GET['error'];
        if (strpos($errorMsg, '%') !== false) {
            $mensaje = urldecode($errorMsg);
        } else {
            switch ($errorMsg) {
                case 'credenciales':
                    $mensaje = 'Correo electrónico o contraseña incorrectos.';
                    break;
                case 'sesion':
                case 'login_required':
                    $mensaje = 'Debes iniciar sesión para acceder a esta página.';
                    break;
                default:
                    $mensaje = $errorMsg;
            }
        }
        $tipo = 'Error';
    }
    
    return ['mensaje' => $mensaje, 'tipo' => $tipo];
}

/**
 * Renderiza select de provincias
 */
function renderizarSelectProvincias($conn, $nombre = 'provincia', $id = 'select-provincia', $requerido = false, $valorSeleccionado = '', $valorEsNombre = false) {
    $atributoRequerido = $requerido ? 'required' : '';
    
    // Si el valor seleccionado es un nombre, obtener el código
    $codigoSeleccionado = $valorSeleccionado;
    if ($valorEsNombre && !empty($valorSeleccionado)) {
        $stmt = $conn->prepare("SELECT Cod_Provincia FROM municipiosjcyl WHERE Provincia = ? LIMIT 1");
        $stmt->execute([$valorSeleccionado]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $codigoSeleccionado = $resultado['Cod_Provincia'] ?? '';
    }
    
    ?>
    <select class="form-input" id="<?= $id ?>" name="<?= $nombre ?>" <?= $atributoRequerido ?>>
        <option value="">Selecciona una provincia</option>
        <?php
        if ($conn) {
            $stmt = $conn->query("SELECT DISTINCT Cod_Provincia, Provincia FROM municipiosjcyl ORDER BY Provincia ASC");
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $seleccionado = ($fila['Cod_Provincia'] == $codigoSeleccionado) ? 'selected' : '';
                echo '<option value="' . $fila['Cod_Provincia'] . '" ' . $seleccionado . '>' . $fila['Provincia'] . '</option>';
            }
        }
        ?>
    </select>
    <?php
}

/**
 * Obtiene el nombre de la provincia a partir del código
 */
function obtenerNombreProvincia($conn, $codigo) {
    if (empty($codigo)) return '';
    
    $stmt = $conn->prepare("SELECT Provincia FROM municipiosjcyl WHERE Cod_Provincia = ? LIMIT 1");
    $stmt->execute([$codigo]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['Provincia'] ?? '';
}

/**
 * Obtiene el código de la provincia a partir del nombre
 */

function obtenerCodigoProvincia($conn, $nombre) {
    if (empty($nombre)) return '';
    
    $stmt = $conn->prepare("SELECT Cod_Provincia FROM municipiosjcyl WHERE Provincia = ? LIMIT 1");
    $stmt->execute([$nombre]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['Cod_Provincia'] ?? '';
}

/**
 * Renderiza la paginación estándar
 */
function renderizarPaginacion($paginaActual, $totalPaginas, $urlBase, $parametrosAdicionales = []) {
    if ($totalPaginas <= 1) return;
    
    $queryString = http_build_query($parametrosAdicionales);
    $separador = $queryString ? '&' : '';
    ?>
    <nav class="mt-16 flex flex-col items-center gap-4">
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            
            <?php if ($paginaActual > 1): ?>
                <a href="<?= $urlBase ?>?<?= $queryString ?><?= $separador ?>pagina=<?= $paginaActual - 1 ?>" 
                   class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            <?php endif; ?>

            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 font-medium">Página</span>
                <select onchange="window.location.href='<?= $urlBase ?>?<?= $queryString ?><?= $separador ?>pagina=' + this.value" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 font-bold outline-none">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <option value="<?= $i ?>" <?= ($i === $paginaActual) ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <span class="text-sm text-gray-500 font-medium">de <?= $totalPaginas ?></span>
            </div>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="<?= $urlBase ?>?<?= $queryString ?><?= $separador ?>pagina=<?= $paginaActual + 1 ?>" 
                   class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            <?php endif; ?>

        </div>
    </nav>
    <?php
}

/**
 * Formatea fecha de forma consistente
 */
function formatearFecha($fecha, $formato = 'd/m/Y') {
    return date($formato, strtotime($fecha));
}

/**
 * Trunca texto de forma segura
 */
function truncar($texto, $longitud = 100, $sufijo = '...') {
    $texto = strip_tags($texto);
    if (strlen($texto) <= $longitud) {
        return $texto;
    }
    return substr($texto, 0, $longitud) . $sufijo;
}