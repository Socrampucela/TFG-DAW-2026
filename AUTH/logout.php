<?php

session_start();


$_SESSION = array();

session_destroy();


header("Location: ../VIEWS/index.php"); 
exit();
?>