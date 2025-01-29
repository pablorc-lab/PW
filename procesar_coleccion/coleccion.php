<?php
session_start();
require_once("../obra.class.inc.php");

// Marcar un elemento si coincide con los valores en la sesión
function marcar($campo, $valor) {
  // Verifica si el campo y el valor en la sesión coinciden con los pasados como argumentos
  if (!empty($_SESSION['campo']) && $_SESSION['campo'] == $campo && !empty($_SESSION['valor_campo']) && $_SESSION['valor_campo'] == $valor) {
      echo 'checked';
  }
}

// Obtenemos todas las obras usando el método estático de la clase Obra
// Comprobamos además si hay filtro
if (!empty($_SESSION['campo']) && !empty($_SESSION['valor_campo'])) {
  list($obras, $filasTotales) = Obra::obtenerObras($_SESSION['campo'], $_SESSION['valor_campo']);
}

else {
  list($obras, $filasTotales) = Obra::obtenerObras();
}

// Comprobar si hay mas de 9 obras y si es asi escribir 
// en la sesión en que página nos encontramos
$paginas_totales = ceil($filasTotales / 9);

if($paginas_totales == 1)
  $_SESSION['num_pag'] = -1;

else if(!isset($_SESSION['num_pag']) || $_SESSION['num_pag'] == -1)
  $_SESSION['num_pag'] = 0;

else {
  // Recorrer las otras páginas y obtener 9 filas empezando por la correspondiente
  for($pagina=1; $pagina<=$paginas_totales; $pagina++) {
    if($_SESSION['num_pag'] == $pagina){
      list($obras, $filasTotales) = Obra::obtenerObras("", "", "titulo", $pagina*9, ($pagina+1)*9);
      break;
    }
  } 
}

// Obtenemos los autores, categorias y anios totales
$autores = Obra::obtenerColumna("autor");
$categorias = Obra::obtenerColumna("categoria");
$anios = Obra::obtenerColumna("anio");


if (!isset($_SESSION['tipo'])) {
  $_SESSION['tipo'] = "";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Colección</title>
    <link rel = "stylesheet" type = "text/css" href = "../pe1/header-footer.css" />
    <link rel = "stylesheet" type = "text/css" href = "coleccion-style-php.css" />
    <style>
      .editar {
        font-size:25px;
        margin: 10px 0;
        padding: 0 30px;
      }

      #aniadir {
        cursor:pointer;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        width: 85%; 
        padding: 10px 0;
        font-size:20px;
        margin: 20px auto;
        margin-left:30px;
        background-color:rgb(241, 227, 69);
        color:rgb(191, 134, 44);
        font-weight: bold;
        border: 1px solid rgb(235, 162, 6);
      }

    </style>
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
          <li><a href="coleccion.php">Colección</a></li>
          <li><a href="../pe1/visita.html">Visita</a></li>
          <li><a href="../pe1/exposiciones.html">Exposiciones</a></li>
          <li><a href="../pe1/informacion.html">Información </a></li>
          <li><a href="../procesar_comentarios/experiencias.php">Experiencias</a></li>
        </ul>
      </nav>

      <!--Formulario inicio sesión de usuario-->
      <?php require "../procesar_usuarios/inicio_sesion.php";?>
    </header>  
      
      <!--Contenido principal-->
    <main>  
      <section class="menu">
        <?php if ($_SESSION['tipo'] == "Admin") : ?>
          <button id="aniadir" type="button" onclick="ventana_aniadir_obra()">Añadir Obra</button>
        <?php endif; ?>

        <nav>
          <form id="filtroForm" action="filtro.php" method="post">
            <input type="hidden" id="campo" name="campo" value="">
            <input type="hidden" id="valor_campo" name="valor_campo" value="">

            <h1>AUTORES</h1>
            <?php foreach ($autores as $autor): ?>
              <div>
                <input style="cursor:pointer;" type="checkbox" name="autor" value="<?= $autor?>" id="<?= $autor?>" onclick="enviarFiltro(event, 'autor', this)" <?= marcar("autor", $autor) ?>>
                <label for="<?= $autor ?>"><?= $autor ?></label>
              </div>
            <?php endforeach; ?>

            <h1>TEMA</h1>
            <?php foreach ($categorias as $categoria): ?>
              <div>
                <input style="cursor:pointer;" type="checkbox" name="categoria" value="<?= $categoria ?>" id="<?= $categoria ?>" onclick="enviarFiltro(event, 'categoria', this)" <?= marcar("categoria", $categoria) ?>>
                <label for="<?= $categoria ?>"><?= $categoria ?></label>
              </div>
            <?php endforeach; ?>

            <h1>ÉPOCA</h1>
            <?php foreach ($anios as $anio): ?>
              <div>
                <input style="cursor:pointer;" type="checkbox" name="anio" value="<?= $anio ?>" id="<?= $anio ?>" onclick="enviarFiltro(event, 'anio', this)" <?= marcar("anio", $anio) ?>>
                <label for="<?= $autor ?>"><?= $anio ?></label>
              </div>
            <?php endforeach; ?>        
          </form>
        </nav>
      </section>
        
      <section class="galeria">
        <?php foreach ($obras as $obra): 
            $datos = $obra->get_datos(); // Obtener los datos de la obra ?>
            <div class="imagen" id="obra-<?=$datos['titulo']?>">
              <img src="<?=$datos['ruta_imagen']?>" class="pasar-imagen" onmouseover="mostrarVentana('<?=$datos['titulo']?>', '<?=$datos['categoria']?>')">
              <h2><?=$datos['titulo']?></h2>
              <p><?=$datos['autor']?></p>
              <p><?=$datos['anio']?></p>

              <?php if($_SESSION['tipo'] == "Admin"): ?>
                  <div style="display:flex; justify-content:space-evenly;">
                      <button 
                          style="background-color:#1f4c3c; color:white; cursor:pointer;" 
                          class="editar"
                          onclick="abrirVentanaModificar('<?=$datos['titulo']?>', '<?=$datos['autor']?>', '<?= $datos['anio'] ?>', '<?=$datos['categoria']?>', '<?=$datos['ruta_imagen']?>')"
                      >Modificar</button>

                      <form action="eliminar_obra.php" method="post">
                          <input type="hidden" name="titulo" value="<?=$datos['titulo']?>">
                          <input 
                              style="background-color:#690000; color:white; cursor:pointer;" 
                              class="editar"  
                              type="submit" 
                              value="Eliminar" 
                              onclick="confirmarEliminacion(event)"
                          >
                      </form>
                  </div>
              <?php endif; ?>
            </div>
        <?php endforeach; ?>
      </section>

    </main>    
    
    <!--Boton de siguientes o anterior segun la página en la que nos encontremos
    Siempre que haya mas de 9 obras-->
    <?php if($_SESSION['num_pag'] != -1): ?>
      <?php if($_SESSION['num_pag'] == 0): ?>
        <section class="boton-sig">
          <form action="cambiar_pag.php" method="GET">
            <input type="hidden" name="num_pag" value="1">
            <button style="color:white; cursor:pointer" type="submit">Siguiente</button>
          </form>
        </section>
      <!--Si no estamos ni en la primera ni en la ultima-->
      <?php elseif($_SESSION['num_pag'] != ($paginas_totales-1)): ?>
        <div style="display:flex; justify-content:space-around;">
          <section class="boton-ant">
            <form action="cambiar_pag.php" method="GET">
              <input type="hidden" name="num_pag" value="<?=($_SESSION['num_pag']-1)?>">
              <button style="color:white; cursor:pointer" type="submit">Anterior</button>
            </form>
          </section>

          <section class="boton-sig">
            <form action="cambiar_pag.php" method="GET">
              <input type="hidden" name="num_pag" value="<?= ($_SESSION['num_pag']+1)?>">
              <button style="color:white; cursor:pointer" type="submit">Siguiente</button>
            </form>
          </section>
      </div>
      <!--Si nos encontramos en la última pag-->
      <?php else :?>
        <section class="boton-ant">
          <form action="cambiar_pag.php" method="GET">
            <input type="hidden" name="num_pag" value="<?= ($_SESSION['num_pag']-1)?>">
            <button style="color:white; cursor:pointer" type="submit">Anterior</button>
          </form>
        </section>
      <?php endif; ?>

    <?php else: ?>
      <style>
        .galeria {
          margin-bottom:200px;
        }
      </style> 
    <?php endif; ?>

    <!--Pie de página-->
    <footer>
      <a href="../pe1/contacto.html">Contacto</a>
      <a href="../pe1/como_se_hizo.pdf">Informe de la práctica</a>
    </footer>
    
    <!--SCRIPTS PARA GESTION DE OBRAS-->
    <script>
      function ventana_aniadir_obra() {
        var ventanaAniadir = window.open('formulario-obra.html', '_blank', 'width=500,height=550,left=50,top=50,scrollbars=yes');
        // Función que se ejecuta cuando la ventana se cierra o se recarga
      }

      function confirmarEliminacion(event) {
        if (!confirm("¿Estás seguro de que deseas eliminar esta obra?")) {
          event.preventDefault(); // Prevenir el envío del formulario si el usuario cancela
        }

        // Si hay una ventana abierta la cerramos
        if(ventanaAbierta !== null) ventanaAbierta.close();
      }

      function abrirVentanaModificar(titulo, autor, anio, categoria, ruta_imagen) {
        // Crear una nueva ventana
        var ventanaModificar = window.open('modificar_obra.html', '_blank', 'width=500,height=550,left=50,top=50,scrollbars=yes');

        // Esperar a que la ventana se cargue completamente antes de acceder a sus elementos
        ventanaModificar.onload = function() {
          // Acceder al formulario en la nueva ventana
          var formulario = ventanaModificar.document.getElementById('formulario');

          // Establecer los valores de los campos del formulario
          formulario.elements['titulo_original'].value = titulo;
          formulario.elements['titulo'].value = titulo;
          formulario.elements['autor'].value = autor;
          formulario.elements['anio'].value = anio;
          formulario.elements['categoria'].value = categoria;
          formulario.elements['ruta_original'].value = ruta_imagen;
          formulario.elements['ruta_imagen'].value = ruta_imagen.split('/').pop();
        };

        // Cerrar la ventana emergente cuando la página se descargue
        window.onbeforeunload = function() {
          ventanaModificar.close();
        };
      }
    </script>

    <!--SCRIPTS PARA FILTRADO-->
    <script>
      function enviarFiltro(event, tipoCampo, elementoSeleccionado) {

        // Obtener el valor de la opción seleccionada
        let valor = elementoSeleccionado.value;

        // Comprobar si la opción estaba previamente seleccionada
        let previamenteSeleccionado = elementoSeleccionado.checked;

        // Si la opción estaba previamente seleccionada, mostrar una alerta
        if (!previamenteSeleccionado) {
          document.getElementById("campo").value = "";
          document.getElementById("valor_campo").value = "";
        }

        else {
          document.getElementById("campo").value = tipoCampo;
          document.getElementById("valor_campo").value = valor;
        }

        // Enviar el formulario
        document.getElementById("filtroForm").submit();
      }
    </script>
    
    <!--SCRIPTS PARA CUANDO SE PASA EL MOUSE POR UNA OBRA-->
    <script>
      var ventanaAbierta = null; // Variable para almacenar la ventana abierta

      function mostrarVentana(titulo, categoria) {
          if (ventanaAbierta) 
              ventanaAbierta.close(); // Cierra la ventana previamente abierta
          
          ventanaAbierta = window.open('', '_blank', 'width=400,height=220,left='+(window.screen.width - 400)+',top=100,scrollbars=yes,status=0');
          ventanaAbierta.document.write(`
            <style>
              body {
                font-family: 'Verdana';
                background-color:#f6f4ba;
              }

              h2{
                font-weight: lighter;
                margin-bottom:0;
                color:#626b00;
              }

              h1 {
                text-align:justify;
                padding:0 10px;
                margin-top:0;
                color:white;
                background-color:1f1f1f;
              }

              div{
                display:flex;
                align-items:center;
              }
            </style>

            <body>
              <h2>Titulo</h2>
              <h1>${titulo}</h1> 

              <h2>Categoría</h2>
              <h1>${categoria}</h1> 
            </body>`
            );
          
         
          // Cerrar la ventana emergente cuando la pestaña no está visible
          document.addEventListener('visibilitychange', function() {
            if (document.hidden && ventanaAbierta) 
              ventanaAbierta.close();
          });
      }
    </script>
</body>
</html>
