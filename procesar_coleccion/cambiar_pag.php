<?php
session_start(); // Inicia la sesión


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['num_pag'] = $_GET['num_pag'];
    header('Location: coleccion.php'); 
    exit;
}
?>
