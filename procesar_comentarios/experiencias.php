<?php 
session_start(); 
require_once("../comentario.class.inc.php");

if (!isset($_SESSION['tipo'])) {
  $_SESSION['tipo'] = "";
}

$comentarios = Comentario::obtenerComentarios();


?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <title>Experiencias</title>
    <link rel = "stylesheet" type = "text/css" href = "../pe1/header-footer.css" />
    <link rel = "stylesheet" type = "text/css" href = "../pe1/experiencias-style.css" />
  </head>

  <body>
    <!--Cabecera-->
    <header>
      <!--Logotipo-->
      <figure> 
        <img id="logo" src="../pe1/imagenes/logo.png " width="100px" height="100px">
        <figcaption>Museo Invictus</figcaption> 
      </figure> 

      <!--Menu de navegación-->
      <nav>
        <ul>
          <li><a href="../procesar_usuarios/index.php">Inicio</a></li>
          <li><a href="../procesar_coleccion/coleccion.php">Colección</a></li>
          <li><a href="visita.html">Visita</a></li>
          <li><a href="exposiciones.html">Exposiciones</a></li>
          <li><a href="informacion.html">Información </a></li>
          <li><a href="experiencias.php">Experiencias</a></li>
        </ul>
      </nav>

      <!--Formulario inicio sesión de usuario-->
      <?php require "../procesar_usuarios/inicio_sesion.php";?>
    </header> 
    
    <main>
       <!--Formulario de opinion-->
      <h1 id="h1-opiniones">Opiniones</h1>

      <?php foreach ($comentarios as $comentario): 
        $datos = $comentario->get_datos(); ?>
        <section class="cotenedor-puntuacion">
            <section class="puntuacion">
                <img src="../pe1/imagenes/usuario.png" width="50px" height="50px"> 
                <section>
                    <p><?= $datos['nombre']?></p>
                    <p id="puntuacion">
                      <?php 
                        $valoracion = $datos["valoracion"];
                        $estrellas_llenas = str_repeat("★", $valoracion); 
                        $estrellas_vacias = str_repeat("✰", 5 - $valoracion); 
                        echo $estrellas_llenas . $estrellas_vacias;
                      ?>
                    </p>
                </section>
            </section>
            <p id="opinion"><?= $datos['comentario']?></p>        
        </section>
      <?php endforeach; ?>
        

      <!--Formulario de opinion SOLO VISIBLE AL INICIAR SESION Y SER CLIENTE-->
      <!--ADEMAS SOLO ES VISIBLE SI EL USUARIO NO HA AÑADIDO NINGUN COMENTARIO-->
      <?php if ($_SESSION['tipo'] == "Cliente" && !Comentario::verificarComentario($_SESSION['correo_usuario'])) : ?>
        <h1 id="h1-formulario">¡Dejanos tu reseña!</h1>
        <form id="formulario" action="aniadir_comentario.php" method="POST">
          <section class="reseña-valoracion">
            <input type="radio" id="valoracion1" name="valoracion" value="1">
            <label for="valoracion1">1</label>
            
            <input type="radio" id="valoracion2" name="valoracion" value="2">
            <label for="valoracion2">2</label>
            
            <input type="radio" id="valoracion3" name="valoracion" value="3">
            <label for="valoracion3">3</label>
            
            <input type="radio" id="valoracion4" name="valoracion" value="4">
            <label for="valoracion4">4</label>
            
            <input type="radio" id="valoracion5" name="valoracion" value="5">
            <label for="valoracion5">5</label>
          </section>
          
          <label id="reseña-titulo" for="reseña-texto">Comentario</label>
          <textarea id="reseña-texto" name="reseña-texto" rows="4" ></textarea>    

          <input type="submit" class="boton-envio" value="PUBLICAR"></input>
        </form>
      <?php else: ?>
      <style>
        main {
          margin-bottom:350px;
        }
      </style> 
      <?php endif; ?>

    </main>
    
    <!--Pie de página-->
    <footer>
      <a href="../pe1/contacto.html">Contacto</a>
      <a href="../pe1/como_se_hizo.pdf">Informe de la práctica</a>
    </footer>

    <script>
      document.getElementById('formulario').addEventListener('submit', function(event) {
        // Obtener el valor del campo de valoración
        var valoracion = document.querySelector('input[name="valoracion"]:checked');
        
        // Verificar si se ha seleccionado una valoración
        if (!valoracion) {
          alert('Por favor, selecciona una puntuación');
          event.preventDefault(); // Detener el envío del formulario
          return;
        }
        
        // Obtener el valor del campo de comentario
        var comentario = document.getElementById('reseña-texto').value;
        
        // Verificar si el comentario tiene al menos ciertos caracteres (por ejemplo, 50)
        if (comentario.length < 50) {
          alert('El comentario debe tener al menos 50 caracteres.');
          event.preventDefault(); // Detener el envío del formulario
          return;
        }
      });
  </script>
  </body>
</html>