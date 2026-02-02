<?php
include_once "../../DAO/usuarioDAO.php";
include_once "../../config/db.php";
session_start();


if (!isset($_SESSION['nombre'])) { header("Location: ../../VIEWS/login.php"); exit; }

$Usuariodao = new usuarioDAO($conn);
$id = $_GET['id'] ?? null;

if ($id && $Usuariodao->borrarUsuario($id)) {
    header("Location: ../../VIEWS/usuarios.php?msg=deleted");
    die();
} else {
    header("Location: ../../VIEWS/usuarios.php?msg=error");
    die();
}

exit;