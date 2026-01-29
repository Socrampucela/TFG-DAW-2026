<?php


$prov_id = isset($_GET['provincia_id']) ? $_GET['provincia_id'] : null;

if ($prov_id) {
    $stmt = $pdo->prepare("SELECT Cod_Municipio, nombre FROM localidades WHERE Cod_Provincia = ? ORDER BY nombre ASC");
    $stmt->execute([$prov_id]);
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($resultados);
}
?>