<?php
include_once '../CLASSES/usuario.php';
include_once '../includes/header.php';
include_once "../DAO/empleoDAO.PHP";
include_once "../config/db.php";
$empleoDAO = new EmpleoDAO($conn);
if(esAdmin()){
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zona de administrador </title>
    </head>
    <body>
        <?= "Bienvenido, $_SESSION[nombre]" ?>
        <div id="apartadosAdministrador"></div> 
        <div id="estadisticas">
        <?php 
        $estadisticas = $empleoDAO->devolverEmpleoProvincia();
        print_r($estadisticas);
        ?>

        </div>
    </body>
    </html>
    <?php
}
else{
    header("Location: index.php");
}