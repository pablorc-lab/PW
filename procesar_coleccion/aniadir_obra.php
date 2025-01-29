<?php

require_once("../obra.class.inc.php");

// Función para mostrar el formulario con un mensaje de error
function mostrarError($mensaje) {
    echo "<h1 style='text-align:center;color:red'>{$mensaje}</h1>";
    echo file_get_contents('formulario-obra.html');
    exit();
}

// Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = array(
        "titulo" => $_POST["titulo"],
        "autor" => $_POST["autor"],
        "anio" => $_POST["anio"],
        "categoria" => $_POST["categoria"],
        "ruta_imagen" => $_POST["ruta_imagen"]
    );
    
    
    /// Insertar la obra y verificar si ya existe el título o la imagen
    $resultado = Obra::insertarObra($datos);

    // Comprobar si el resultado indica que el título ya existe
    if ($resultado === "titulo") 
        mostrarError("EL TÍTULO DE LA OBRA YA EXISTE");

    // Comprobar si la imagen no existe
    else if ($resultado === "imagen_inexistente") 
        mostrarError("LA IMAGEN NO EXISTE");

    // Comprobar si el resultado indica que la imagen ya está almacenada
    else if ($resultado === "imagen_almacenada") 
        mostrarError("LA IMAGEN YA ESTÁ ALMACENADA");
 
    else{
        // Mostrar un mensaje de éxito si la obra se añadió correctamente
        echo "
        <div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'>
            <h1 style='text-align: center; color: green; font-size:40px;'>OBRA AÑADIDA CON ÉXITO</h1>
            <h2>Puede cerrar esta ventana o crear otra obra</h2> 
            <div style='display:flex; justify-content:space-between;'>        
                <button style='width: 200px; font-size: 25px; background-color:cyan;padding:10px; border-radius:15px; margin-right:10px;'>
                    <a href='formulario-obra.html' style='text-decoration:none; color:black;'>Añadir obra</a>
                </button>
                <button id='cerrarVentana' style='color:white; width: 200px; font-size: 25px; background-color:brown; padding:10px; border-radius:15px; margin-left:10px; cursor:pointer;'>
                    Cerrar ventana
                </button>
            </div>
        </div>
        <script>
            
            
            document.getElementById('cerrarVentana').addEventListener('click', function() {
                window.close();
                // Recargar la ventana principal cuando la secundaria se cierre
                window.opener.location.reload();
            });

        </script>";    
    }
    
}

else {
    echo "<h1>ERROR : No se ha realizado correctamente la solicitud POST</h1>";
}
?>
