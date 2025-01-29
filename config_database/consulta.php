<?php
    require_once("../usuario.class.inc.php");
    require_once("../obra.class.inc.php");
    require_once("../comentario.class.inc.php");
  
    // Obtener usuarios 
    list($usuarios, $totalUsuarios) = Usuario::obtenerUsuarios("nombre", 0, 20);

    // Obtener obras
    list($obras, $totalObras) = Obra::obtenerObras("", "", "titulo", 0, 30);
    
    // Obtener comentarios
    $comentarios = Comentario::obtenerComentarios();
    $totalComentarios = count($comentarios);


?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <th>Correo</th>
            <th>Contraseña</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>País</th>
            <th>Ciudad</th>
            <th>Teléfono</th>
            <th>Fecha de Nacimiento</th>
            <th>Tipo</th>
        </tr>

        <?php echo "<h1>Usuarios Totales : $totalUsuarios </h1>";?>
        <?php foreach ($usuarios as $usuario): 
            $datos = $usuario->get_datos(); ?>
            <tr>
                <td><?php echo $datos['correo']; ?></td>
                <td><?php echo $datos['contrasenia']; ?></td>
                <td><?php echo $datos['nombre']; ?></td>
                <td><?php echo $datos['apellidos']; ?></td>
                <td><?php echo $datos['pais']; ?></td>
                <td><?php echo $datos['ciudad']; ?></td>
                <td><?php echo $datos['telefono']; ?></td>
                <td><?php echo $datos['fechaNacimiento']; ?></td>
                <td><?php echo $datos['tipo']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <table>
        <tr>
            <th>Titulo</th>
            <th>Autor</th>
            <th>Anio</th>
            <th>Categoria</th>
            <th>Ruta de la imagen</th>
        </tr>

        <?php echo "<br><h1>Obras Totales : $totalObras </h1>";?>
        <?php foreach ($obras as $obra): 
            $datos = $obra->get_datos(); ?>
            <tr>
                <td><?php echo $datos['titulo']; ?></td>
                <td><?php echo $datos['autor']; ?></td>
                <td><?php echo $datos['anio']; ?></td>
                <td><?php echo $datos['categoria']; ?></td>
                <td><?php echo $datos['ruta_imagen']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <table>
        <tr>
            <th>Correo</th>
            <th>Nombre completo</th>
            <th>Valoración</th>
            <th>Comentario</th>
        </tr>

        <?php echo "<br><h1>Comentarios Totales : $totalComentarios </h1>";?>
        <?php foreach ($comentarios as $comentario): 
            $datos = $comentario->get_datos(); ?>
            <tr>
                <td><?php echo $datos['correo']; ?></td>
                <td><?php echo $datos['nombre']; ?></td>
                <td><?php echo $datos['valoracion']; ?></td>
                <td><?php echo $datos['comentario']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
