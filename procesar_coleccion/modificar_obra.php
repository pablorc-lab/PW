<?php
session_start();
require_once("../obra.class.inc.php");

 // Funcion para mostrar el error encontrado
function mostrarError($mensaje) {
    echo "<script>
        alert('$mensaje');
        window.history.back();
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Guardamos en la sesión actual los valores del titulo original y ruta original
    // por si el usuario quiere volver a dejar esos valores ya que estos no se ajustan
    // cuando da error ya que se vuelve para atras en la ventana eliminado estos valores hidden
    if(!isset($_SESSION['titulo_original']) || $_SESSION['titulo_original'] == "")
        $_SESSION['titulo_original'] = $_POST['titulo_original'];

    if(!isset($_SESSION['ruta_original']) || $_SESSION['ruta_original'] == "")
        $_SESSION['ruta_original'] = $_POST['ruta_original'];

    // Obtener los datos del formulario
    $datos = array(
        "titulo" => $_POST['titulo'],
        "autor" => $_POST['autor'],
        "anio" => $_POST['anio'],
        "categoria" => $_POST['categoria'],
        "ruta_imagen" => $_POST['ruta_imagen'],
        "titulo_original" => $_SESSION['titulo_original'],
        "ruta_original" => $_SESSION['ruta_original'],
    );

    
    // Actualizar la información de la obra
    $resultado = Obra::modificarObra($datos);

        // Verificar el resultado de la modificación
    if ($resultado === "titulo") 
        mostrarError("El TITULO ya está almacenado en la Base de Datos");
    
    else if ($resultado === "imagen_almacenada") 
        mostrarError("La IMAGEN ya está almacenada en la Base de Datos");
    
    else if ($resultado === "imagen_inexistente") 
        mostrarError("La IMAGEN NO existe");

    else{
        // Borramos las variables de la sesión para poder modificar otras obras
        $_SESSION['titulo_original'] = "";
        $_SESSION['ruta_original'] = "";

        echo "
        <div id='contenidoDiv' style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'>
            <h1 style='text-align: center; color: green; font-size:40px;'>OBRA MODIFICADA CON ÉXITO</h1>
            <button style='font-size:25px; background-color:rgb(4, 175, 255);border-radius:15px; padding:15px; cursor:pointer;' id='cerrarVentana'>Cerrar Ventana</button> 
        </div>
        <script>

            document.getElementById('cerrarVentana').addEventListener('click', function() {
                window.close();
                window.opener.location.reload(); // Recargar la página principal

            });
        </script>";
    }    
} 

else {
    echo "Método de solicitud no permitido.";
}
?>
