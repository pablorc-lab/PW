<?php
require_once ("datosObject.class.inc.php");

class Comentario extends DataObject {
    // Valores
    protected $datos = array(
        "correo" => "",
        "nombre" => "",
        "valoracion" => "",
        "comentario" => "",
    );

    // Métodos
    public static function obtenerComentarios() {
        $conexion = parent::conectar();
        
        $sql = "SELECT * FROM " . TABLA_COMENTARIOS;
    
        try {
            $st = $conexion->query($sql);
            $comentarios = array();
            foreach ($st->fetchAll() as $fila) {
                // Crear una nueva instancia de Comentario y almacenarla en el array de comentarios
                $comentario = new Comentario($fila);
                $comentarios[] = $comentario;
            }
            parent::desconectar($conexion);
            return $comentarios;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("Error al obtener los comentarios: " . $e->getMessage());
        }
    }
    
    
    // Esta función se encarga de pasandole un correo, verificar si ya hay un comentario
    // escrito por ese usuario de forma que no pueda hacer mas
    public static function verificarComentario($correo) {
        $conexion = parent::conectar();
            
        // Construir la consulta SQL
        $sql = "SELECT * FROM " . TABLA_COMENTARIOS . " WHERE correo = :correo";
        
        try {
            $st = $conexion->prepare($sql); // Preparamos la consulta
            $st->bindValue(":correo", $correo, PDO::PARAM_STR); // Asignar valor para el correo
            $st->execute();
                
            $comentario = $st->fetch(); // Obtenemos el comentario encontrado
            parent::desconectar($conexion);
        
            // Si se encuentra el comentario, devuelve true; de lo contrario, devuelve false
            return $comentario ? true : false;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("BÚSQUEDA DE COMENTARIO FALLIDA: " . $e->getMessage());
        }
    }
        
    // Funcion para eliminar un comentario
    public static function eliminarComentario($correo) {
        // Verificar si el parámetro de correo es nulo
        if ($correo == null)
            throw new Exception("El parámetro de correo es nulo. Se requiere un correo para eliminar el comentario");
        
        $conexion = parent::conectar();
        
        // Verificar si existe un comentario para el correo dado
        if (!self::verificarComentario($correo)) {
            return false;
        }

        // Construir la consulta SQL para eliminar el comentario con el correo dado
        $sql = "DELETE FROM " . TABLA_COMENTARIOS . " WHERE correo = :correo";
        
        try {
            $st = $conexion->prepare($sql); // Preparamos la consulta
            $st->bindValue(":correo", $correo, PDO::PARAM_STR); // Asignar valor para el correo
            $st->execute();
                
            // Devolver true si se eliminó correctamente, de lo contrario, devolver false
            return $st->rowCount() > 0 ? true : false;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("ELIMINACIÓN DE COMENTARIO FALLIDA: " . $e->getMessage());
        }
    }
        

    // Inserta el comentario en la base de datos y devuelve una instancia del mismo
    public static function insertarComentario($datos) {
        // Conectar a la base de datos
        $conexion = parent::conectar();
    
        try {
            // Preparar la consulta SQL de inserción
            $sql = "INSERT INTO " . TABLA_COMENTARIOS . " (correo, nombre, valoracion, comentario) " 
                   . "VALUES (:correo, :nombre, :valoracion, :comentario)";
    
            // Preparar la consulta
            $st = $conexion->prepare($sql);
    
            // Asignar valores a los marcadores de parámetros
            $st->bindValue(":correo", $datos["correo"], PDO::PARAM_STR);
            $st->bindValue(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $st->bindValue(":valoracion", $datos["valoracion"], PDO::PARAM_INT);
            $st->bindValue(":comentario", $datos["comentario"], PDO::PARAM_STR);
    
            // Ejecutar la consulta
            $st->execute();
    
            // Cerrar la conexión
            parent::desconectar($conexion);
    
            // Devolver "exito" si la inserción fue exitosa
            return new Comentario($datos);
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            // Manejar errores
            die("NO SE PUDO PUBLICAR EL: " . $e->getMessage());
        }
    }
        
}
?>  