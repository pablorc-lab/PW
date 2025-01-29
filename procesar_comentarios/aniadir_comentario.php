<?php 
session_start(); 

require_once('../comentario.class.inc.php');
require_once('../usuario.class.inc.php');

// Verificar si se enviaron datos del formulario y que además sean correctos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_SESSION['correo_usuario'];
    $usuario = Usuario::obtenerUsuario($correo);
    $nombre_completo = $usuario["nombre"] .  " " . $usuario["apellidos"];

    $datos = array(
        "correo" => $correo,
        "nombre" => $nombre_completo,
        "valoracion" => $_POST["valoracion"],
        "comentario" => $_POST["reseña-texto"]
    );

    Comentario::insertarComentario($datos);
    header('Location: experiencias.php');

} 

else {
    echo "<script>alert('ERROR : No se ha realizado correctamente la solicitud');</script>";
    echo "<script>window.location.href = 'experiencias.php';</script>";
}

?>