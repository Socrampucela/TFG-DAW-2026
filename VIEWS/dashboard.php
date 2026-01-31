<?php
include_once '../CLASSES/usuario.php';
include_once '../includes/header.php';


if(esAdmin()){
    print "Bienvenido, $_SESSION[nombre]";
}
else{
    header("Location: index.php");
}