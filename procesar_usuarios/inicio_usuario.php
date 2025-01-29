<?php
session_start(); // Iniciar la sesión
require_once('../usuario.class.inc.php');

// Obtener el correo del formulario, supongamos que se envía mediante POST
$correo = $_POST['campoUsuario'];
$contrasenia = $_POST['campoContraseña'];
$usuario = Usuario::obtenerUsuario($correo, $contrasenia);

if($usuario){
    // Almacenar información del usuario en la sesión
    $_SESSION['nombre_usuario'] = $usuario['nombre']; 
    $_SESSION['correo_usuario'] = $usuario['correo'];
    $_SESSION['tipo'] = $usuario['tipo']; 

    // Redirigir a la página de inicio o dashboard del usuario
    header('Location: index.php');
    exit();
}

else {
    // Mostrar alerta y volver atrás
    echo '<script>
            alert("Usuario no encontrado. Por favor, verifique su correo y contraseña.");
            window.history.back();
        </script>';
}

?>


