<?php
require_once ("datosObject.class.inc.php");
require_once ("comentario.class.inc.php");

class Usuario extends DataObject {
    // Valores
    protected $datos = array(
        "correo" => "",
        "contrasenia" => "",
        "nombre" => "",
        "apellidos" => "",
        "pais" => "",
        "ciudad" => "",
        "telefono" => "",
        "fechaNacimiento" => "",
        "tipo" => "",
    );

    // Métodos
    public static function obtenerUsuarios($orden = "nombre", $filaInicio = 0, $numeroFilas = 10) {
        $conexion = parent::conectar(); 
        // Usamos SQL_CALC_FOUND_ROWS para obtener más adelante cuántas filas fueron seleccionadas sin tener en cuenta el LIMIT
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TABLA_USUARIOS . "
                ORDER BY $orden 
                LIMIT :filaInicio, :numeroFilas"; 

        try { 
            $st = $conexion->prepare($sql); 
            $st->bindValue(":filaInicio", $filaInicio, PDO::PARAM_INT); 
            $st->bindValue(":numeroFilas", $numeroFilas, PDO::PARAM_INT); 
            $st->execute(); 
            $usuarios = array(); 
            foreach ($st->fetchAll() as $fila) { 
                // Crear un nuevo usuario y almacenarlo en el array de usuarios
                $usuario = new Usuario($fila); // Crea una nueva instancia de Usuario
                $usuarios[] = $usuario; 
                
                // Imprimir los valores del usuario
                //self::imprimirDatosUsuario($fila);
            } 
            $st = $conexion->query("SELECT FOUND_ROWS() AS filasTotales");
            // Obtenemos la primera fila (en realidad, la única) de la salida de la query, almacenada en $st 
            $fila = $st->fetch(); 
            parent::desconectar($conexion); 
            return array($usuarios, $fila["filasTotales"]); 
        } 
        catch (PDOException $e) { 
            parent::desconectar($conexion); 
            die("Consulta fallida: " . $e->getMessage()); 
        } 
    } 
    

    // Devuelve true si encuentra un usuario según su correo electrónico y contraseña (opcional), false si no lo encuentra
    public static function obtenerUsuario($correo, $contrasenia = null) {
        $conexion = parent::conectar();
        
        // Definir la consulta SQL base
        $sql = "SELECT * FROM " . TABLA_USUARIOS . " WHERE correo = :correo";

        // Si se proporciona una contraseña, incluir la condición en la consulta
        if ($contrasenia !== null) {
            $sql .= " AND contrasenia = :contrasenia";
        }

        try {
            $st = $conexion->prepare($sql); // Preparamos la consulta
            $st->bindValue(":correo", $correo, PDO::PARAM_STR); // Asignar valor para correo
            
            // Si se proporciona una contraseña, asignar valor para contrasenia
            if ($contrasenia !== null) {
                $st->bindValue(":contrasenia", $contrasenia, PDO::PARAM_STR);
            }

            $st->execute();
            
            $usuario = $st->fetch(); // Obtenemos el usuario encontrado
            parent::desconectar($conexion);

            // Si se encuentra un usuario, lo devuelve; de lo contrario, devuelve false
            return $usuario;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("OBTENCIÓN DEL USUARIO FALLIDA: " . $e->getMessage());
        }
    }



    // Inserta el USUARIO en la base de datos y devuelve una instancia del mismo
    public static function insertarUsuario($datos) {
        // Conectar a la base de datos
        $conexion = parent::conectar();
    
        // Preparar la consulta SQL de inserción
        $sql = "INSERT INTO " . TABLA_USUARIOS . " (correo, contrasenia, nombre, apellidos, pais, ciudad, telefono, fechaNacimiento, tipo) " 
               . "VALUES (:correo, :contrasenia, :nombre, :apellidos, :pais, :ciudad, :telefono, :fechaNacimiento, :tipo)";
    
        try {
            // Preparar la consulta
            $st = $conexion->prepare($sql);
    
            // Asignar valores a los marcadores de parámetros
            $st->bindValue(":correo", $datos["correo"], PDO::PARAM_STR);
            $st->bindValue(":contrasenia", $datos["contrasenia"], PDO::PARAM_STR);
            $st->bindValue(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $st->bindValue(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
            $st->bindValue(":pais", $datos["pais"], PDO::PARAM_STR);
            $st->bindValue(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
            $st->bindValue(":telefono", $datos["telefono"], PDO::PARAM_INT);
            $st->bindValue(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
            $st->bindValue(":tipo", $datos["tipo"], PDO::PARAM_STR);

            // Ejecutar la consulta
            $st->execute();
    
            // Cerrar la conexión
            parent::desconectar($conexion);

            // Devolver un nuevo objeto Usuario con los datos proporcionados
            return new Usuario($datos);
        } 
        
        catch (PDOException $e) {
            parent::desconectar($conexion);
            // Manejamos el error específico de violación de unicidad del número de teléfono
            if ($e->getCode() == "23000") {
                echo "<script>alert('El número de teléfono ya está en uso. Por favor, inténtalo con otro número.');</script>";
                echo "<script>window.history.back();</script>";
                exit; 
            } 
            else {
                // Manejar otros errores
                echo "<script>alert('No se puedo añadir el usuario, intentelo de nuevo');</script>";
                echo "<script>window.history.back();</script>";
                exit; 
            }
        }
    }


    // Función para eliminar un USUARIO según el CORREO dado
    public static function eliminarUsuario($correo) {
        $conexion = parent::conectar();
        
        $sql = "DELETE FROM " . TABLA_USUARIOS . " WHERE correo = :correo";
        
        try {
            // Si finalmente se ha eliminad, también lo haremos con su comentario asociado
            Comentario::eliminarComentario($correo);

            $st = $conexion->prepare($sql); // Preparamos la consulta
            $st->bindValue(":correo", $correo, PDO::PARAM_STR); // Asignar valores
            $st->execute();
                       
            parent::desconectar($conexion);
        
            // Obtener el número de filas afectadas
            // Verificar si se eliminó el usuario correctamente
            return $st->rowCount() > 0; //True si se ha eliminado, False si no
        } 
        
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("ELIMINACIÓN DEL USUARIO FALLIDA: " . $e->getMessage());
        }
    }
    

}
?>  