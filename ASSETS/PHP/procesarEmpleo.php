<?php
session_start();
require_once "../../CONFIG/db.php";
require_once "../../CLASSES/empleo.php";
require_once "../../DAO/empleoDAO.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['nombre'])) {

    $empleo = new Empleo(
        $_POST['titulo'],
        $_POST['provincia'],  
        $_POST['descripcion'],
        $_POST['localidad'],   
        $_POST['enlace']
    );

    $dao = new EmpleoDAO($conn);

    if ($dao->insertar($empleo)) {
        header("Location: ../../VIEWS/index.php");
    } else {
        header("Location: ../../VIEWS/crearEmpleo.php?error=db_error");
    }
} else {
    header("Location: ../../VIEWS/login.php");
}