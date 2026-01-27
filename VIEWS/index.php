<?php
require_once '../CONFIG/db.php';

$sql = "SELECT * FROM `ofertas_de_empleo` ORDER BY `ofertas_de_empleo`.`Fecha publicación` ASC LIMIT 10";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        echo "<h3>" . $row['Título'] . "</h3>";
         echo "<h3>" . $row['Provincia'] . "</h3>";
    }
} else {
    echo "No hay datos.";
}