<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titulo = $_POST["titulo"];
    $provincia = $_POST["provincia"]; //ID PROVINCIA
    $localidad = $_POST["localidad"]; //ID LOCALIDAD
    
}
else{
    header("location:../VIEWS/crearEmpleo.php?error=ErrorFormulario");
}