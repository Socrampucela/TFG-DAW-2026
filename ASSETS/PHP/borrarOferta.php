<?php
include_once "../DAO/empleoDAO.php";
include_once "../config/db.php";
session_start();


if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }

$dao = new EmpleoDAO($conn);
$id = $_GET['id'] ?? null;

if ($id && $dao->borrarOferta($id)) {
    header("Location: ../VIEWS/ofertas.php?msg=deleted");
    die();
    } else {
    header("Location: ../VIEWS/ofertas.php?msg=error");
    die();
    }
exit;