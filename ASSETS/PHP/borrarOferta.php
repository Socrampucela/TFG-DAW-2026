<?php
include_once "../../DAO/empleoDAO.php";
include_once "../../config/db.php";
session_start();


if (!isset($_SESSION['nombre'])) { header("Location: ../../VIEWS/login.php"); exit; }

$Empleodao = new EmpleoDAO($conn);
$id = $_GET['id'] ?? null;

if ($id && $Empleodao->borrarOferta($id)) {
    header("Location: ../../VIEWS/ofertas.php?msg=deleted");
    die();
    } else {
    header("Location: ../../VIEWS/ofertas.php?msg=error");
    die();
    }
exit;