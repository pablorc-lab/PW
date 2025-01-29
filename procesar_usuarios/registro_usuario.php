<?php

require_once('../usuario.class.inc.php');

// Verificar si se enviaron datos del formulario y que además sean correctos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear un array con los datos del formulario
    $datos = array(
        "correo" => $_POST['campoCorreo'],
        "contrasenia" => $_POST['campoContraseña'],
        "nombre" => $_POST['campoNombre'],
        "apellidos" => $_POST['campoApellidos'],
        "pais" => $_POST['listaPaises'],
        "ciudad" => $_POST['campoCiudad'],
        "telefono" => $_POST['campoDeTelefono'],
        "fechaNacimiento" => $_POST['campoDeFecha'],
        "tipo" => "Cliente",
    );
    
    
    // Verificar si el correo electrónico ya existe en la base de datos
    if (!Usuario::obtenerUsuario($datos['correo'])) {
        // Insertamos al usuario si no existe previamente
        Usuario::insertarUsuario($datos);
        // Redirigir a altacorrecta.html
        header('Location: ../pe1/altacorrecta.html');
    } 
    
    else {
        echo "<script>alert('El correo electrónico ya está en uso');</script>";
        echo "<script>window.history.back();</script>";
    }
} 

else {
    echo "<script>ERROR : No se ha realizado correctamente la solicitud POST</script>";
    echo "<script>window.history.back();</script>";
}
?>
