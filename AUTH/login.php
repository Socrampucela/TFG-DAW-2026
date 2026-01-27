<?php
if($_SERVER['REQUEST_METHOD'] === "GET"){
   if($_GET["registro"] == "exito"){
    print("Se ha creado con exito la cuenta");
   }
}