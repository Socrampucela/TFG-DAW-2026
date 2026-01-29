<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../CONFIG/db.php';
require_once __DIR__ . '/../DAO/empleoDAO.PHP';

try {
    $dao = new empleo($conn);

    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $provincia = isset($_GET['provincia']) ? trim($_GET['provincia']) : '';
    $fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : ''; // '' | 24h | 7d | 30d

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $pageSize = 12;
    $inicio = ($page - 1) * $pageSize;

    $total = $dao->contarFiltrados($q, $provincia, $fecha);
    $items = $dao->buscarEmpleosPaginado($q, $provincia, $fecha, $inicio, $pageSize);

    foreach ($items as &$it) {
        $it['DescripcionTexto'] = isset($it['Descripción']) ? trim(strip_tags($it['Descripción'])) : '';
    }

    echo json_encode([
        'ok' => true,
        'page' => $page,
        'pageSize' => $pageSize,
        'total' => $total,
        'pages' => (int)ceil($total / $pageSize),
        'items' => $items
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Error interno al buscar ofertas.'
    ], JSON_UNESCAPED_UNICODE);
}
