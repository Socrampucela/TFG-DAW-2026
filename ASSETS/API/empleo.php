<?php
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . "/../../config/db.php");
require_once(__DIR__ . "/../../DAO/EmpleoDAO.php");

try {
    $dao = new empleo($conn); // tu clase se llama empleo (case-insensitive en PHP)

    $titulo    = isset($_GET['titulo']) ? trim($_GET['titulo']) : '';
    $provincia = isset($_GET['provincia']) ? trim($_GET['provincia']) : '';
    $localidad = isset($_GET['localidad']) ? trim($_GET['localidad']) : '';
    $dias      = isset($_GET['dias']) ? (int)$_GET['dias'] : 0;

    $page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $pageSize = isset($_GET['pageSize']) ? max(1, min(50, (int)$_GET['pageSize'])) : 20;
    $inicio   = ($page - 1) * $pageSize;

    // whitelist días (evita valores raros)
    $allowedDias = [0, 7, 30, 90];
    if (!in_array($dias, $allowedDias, true)) $dias = 0;

    $filtros = [
        'titulo' => $titulo,
        'provincia' => $provincia,
        'localidad' => $localidad,
        'dias' => $dias
    ];

    $items = $dao->buscarEmpleosPaginado($filtros, $inicio, $pageSize);
    $total = $dao->contarFiltrados($filtros);

    echo json_encode([
        'items' => $items,
        'total' => (int)$total,
        'page' => $page,
        'pageSize' => $pageSize,
        'totalPages' => (int)ceil($total / $pageSize)
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error interno',
        'details' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
