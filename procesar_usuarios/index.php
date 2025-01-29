<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <title>Inicio</title>
    <script src="inicio_sesion.js"></script>
    <link rel = "stylesheet" type = "text/css" href = "../pe1/header-footer.css" />
    <link rel = "stylesheet" type = "text/css" href = "../pe1/index-style.css" />   
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
          <li><a href="index.php">Inicio</a></li>
          <li><a href="../procesar_coleccion/coleccion.php">Colección</a></li>
          <li><a href="../pe1/visita.html">Visita</a></li>
          <li><a href="../pe1/exposiciones.html">Exposiciones</a></li>
          <li><a href="../pe1/informacion.html">Información </a></li>
          <li><a href="../procesar_comentarios/experiencias.php">Experiencias</a></li>
        </ul>
      </nav>

    <!--Formulario inicio sesión de usuario-->
    <?php require "inicio_sesion.php";?>

    </header> 
    
    <!--Contenido principal-->
    <main>
      <h1>Museo Invictus</h1>
      <p>Arte en gigapíxeles, experiencias únicas. ¡Un mundo de historia!</p>
    </main>
    
    <!--Pie de página-->
    <footer>
      <a href="../pe1/contacto.html">Contacto</a>
      <a href="../pe1/como_se_hizo.pdf">Informe de la práctica</a>
    </footer>
  </body>
</html>