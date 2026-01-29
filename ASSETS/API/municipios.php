<?php
require_once '../../CONFIG/db.php'; 

$cod_prov = $_GET['provincia_id'] ?? null;

if ($cod_prov && isset($conn)) {
    try {
        $stmt = $conn->prepare("SELECT Cod_Municipio, Municipio FROM municipiosjcyl WHERE Cod_Provincia = ? ORDER BY Municipio ASC");
        $stmt->execute([$cod_prov]);
    
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($resultados);

    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }       
} else {
    header('Content-Type: application/json');
    echo json_encode([]); 
}
?>