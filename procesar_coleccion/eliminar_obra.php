<?php
require_once("../obra.class.inc.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    
    Obra::eliminarObra($titulo);
    header('Location: coleccion.php'); 

}
?>
