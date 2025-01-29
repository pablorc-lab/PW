<?php
session_start();

require_once("../obra.class.inc.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el valor y el nombre del input enviado y guardarlos en la sesión
    $_SESSION['campo'] = $_POST['campo'];
    $_SESSION['valor_campo'] = $_POST['valor_campo'];

    // Cargar de nuevo la página de colección
    header("Location: coleccion.php");

}
?>
