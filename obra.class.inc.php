<?php
require_once ("datosObject.class.inc.php");

class Obra extends DataObject {
    // Valores
    protected $datos = array(
        "titulo" => "",
        "autor" => "",
        "anio" => "",
        "categoria" => "",
        "ruta_imagen" => ""
    );

    // Métodos
    public static function obtenerObras($campo = "", $filtro = "", $orden = "titulo", $filaInicio = 0, $numeroFilas = 9) {
        $conexion = parent::conectar();
        // Usamos SQL_CALC_FOUND_ROWS para obtener más adelante cuántas filas fueron seleccionadas sin tener en cuenta el LIMIT
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TABLA_OBRAS;

        // Agregar condición de filtro si se proporciona
        if (!empty($campo) && !empty($filtro)) 
            $sql .= " WHERE $campo = '$filtro'";
        
        $sql .= " ORDER BY CAST(REGEXP_REPLACE($orden, '[^0-9]', '') AS UNSIGNED), $orden LIMIT :filaInicio, :numeroFilas";

        try {
            $st = $conexion->prepare($sql);
            $st->bindValue(":filaInicio", $filaInicio, PDO::PARAM_INT);
            $st->bindValue(":numeroFilas", $numeroFilas, PDO::PARAM_INT);
            $st->execute();
            $obras = array();
            foreach ($st->fetchAll() as $fila) {
                // Crear una nueva obra y almacenarla en el array de obras
                $obra = new Obra($fila); // Crea una nueva instancia de Obra
                $obras[] = $obra;
                
                // Imprimir los valores de la obra
                //self::imprimirDatosObra($fila);
            }
            $st = $conexion->query("SELECT FOUND_ROWS() AS filasTotales");
            // Obtenemos la primera fila (en realidad, la única) de la salida de la query, almacenada en $st
            $fila = $st->fetch();
            parent::desconectar($conexion);
            return array($obras, $fila["filasTotales"]);
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("Consulta para obtener las obras fallida: " . $e->getMessage());
        }
    }
    
    
    // Busca una obra en la base de datos según su título
    public static function obtenerObra($titulo = null, $ruta_imagen = null) {
        // Verificar si ambos parámetros son nulos
        if ($titulo === null && $ruta_imagen === null)
            throw new Exception("Ambos parámetros son nulos. Se requiere al menos uno para buscar la obra");
        
        $conexion = parent::conectar();
        
        // Construir la consulta SQL dinámicamente
        $sql = "SELECT * FROM " . TABLA_OBRAS . " WHERE 1=1"; // Se usa WHERE 1=1 para evitar problemas con las condiciones dinámicas
        
        if ($titulo !== null) {
            $sql .= " AND titulo = :titulo";
        }

        if ($ruta_imagen !== null) {
            $sql .= " AND ruta_imagen = :ruta_imagen";
        }

        try {
            $st = $conexion->prepare($sql); // Preparamos la consulta

            // Asignar valores a los marcadores de parámetros si se proporcionan
            if ($titulo !== null) {
                $st->bindValue(":titulo", $titulo, PDO::PARAM_STR);
            }
            if ($ruta_imagen !== null) {
                $st->bindValue(":ruta_imagen", $ruta_imagen, PDO::PARAM_STR);
            }

            $st->execute();
            
            $obra = $st->fetch(); // Obtenemos la obra encontrada
            parent::desconectar($conexion);

            // Si se encuentra la obra, la devuelve; de lo contrario, devuelve false
            return $obra ? $obra : false;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("BÚSQUEDA DE OBRA FALLIDA: " . $e->getMessage());
        }
    }

    
    

    // Inserta el USUARIO en la base de datos y devuelve una instancia del mismo
    public static function insertarObra($datos) {
        // Conectar a la base de datos
        $conexion = parent::conectar();
        
        try {
            // Verificar si el título ya existe
            if (self::obtenerObra($datos["titulo"])) {
                // Si el título ya existe, devolver "titulo"
                return "titulo";
            }
            
            // Verificar primero que la imagen exista
            if(!self::get_imagen("/home/pabloramblado/public_html/pe2/pe1/imagenes/" . $datos["ruta_imagen"])) {
                return "imagen_inexistente";
            }

            // Verificar si la imagen ya está almacenada o no existe
            if (self::obtenerObra(null, $datos["ruta_imagen"])) {
                return "imagen_almacenada";
            }
            
            // Finalmente ajustamos la ruta accesible desde nuestra carpeta de /procesar_obra
            $datos["ruta_imagen"] = "../pe1/imagenes/" . $datos["ruta_imagen"];
            
            // Preparar la consulta SQL de inserción
            $sql = "INSERT INTO " . TABLA_OBRAS . " (titulo, autor, anio, categoria, ruta_imagen) " 
                   . "VALUES (:titulo, :autor, :anio, :categoria, :ruta_imagen)";
    
            // Preparar la consulta
            $st = $conexion->prepare($sql);
    
            // Asignar valores a los marcadores de parámetros
            $st->bindValue(":titulo", $datos["titulo"], PDO::PARAM_STR);
            $st->bindValue(":autor", $datos["autor"], PDO::PARAM_STR);
            $st->bindValue(":anio", $datos["anio"], PDO::PARAM_STR);
            $st->bindValue(":categoria", $datos["categoria"], PDO::PARAM_STR);
            $st->bindValue(":ruta_imagen", $datos["ruta_imagen"], PDO::PARAM_STR);
    
            // Ejecutar la consulta
            $st->execute();
    
            // Cerrar la conexión
            parent::desconectar($conexion);
    
            // Devolver "exito" si la inserción fue exitosa
            return new Obra($datos);

        } catch (PDOException $e) {
            parent::desconectar($conexion);
            // Manejar errores
            die("NO SE PUDO INSERTAR LA OBRA: " . $e->getMessage());
        }
    }
    
    

    public static function modificarObra($datos) {
        // Conectar a la base de datos
        $conexion = parent::conectar();

        try {

            // Comprobar si el título ya existe (exceptuando la obra que se va a modificar)
            $sql = "SELECT COUNT(*) FROM " . TABLA_OBRAS . " WHERE titulo = :titulo AND titulo != :titulo_original";
            $titulo = $conexion->prepare($sql);
            $titulo->bindValue(":titulo_original", $datos["titulo_original"], PDO::PARAM_STR);
            $titulo->bindValue(":titulo", $datos["titulo"], PDO::PARAM_STR);
            $titulo->execute();
            $countTitulo = $titulo->fetchColumn();

            if ($countTitulo > 0 )
                return "titulo";
            

            //Primero modificamos la ruta de la imagen
            // Comprobamos que ese nombre de imagen exista
            if (!self::get_imagen("/home/pabloramblado/public_html/pe2/pe1/imagenes/".$datos["ruta_imagen"])) 
                return "imagen_inexistente";
            
            $datos["ruta_imagen"] = "../pe1/imagenes/" . $datos["ruta_imagen"];
            
            // Comprobar si la ruta de la imagen ya existe (exceptuando la obra que se va a modificar)
            $sql = "SELECT COUNT(*) FROM " . TABLA_OBRAS . " WHERE ruta_imagen = :ruta_imagen AND ruta_imagen != :ruta_original";
            $imagen = $conexion->prepare($sql);
            $imagen->bindValue(":ruta_original", $datos["ruta_original"], PDO::PARAM_STR);
            $imagen->bindValue(":ruta_imagen", $datos["ruta_imagen"], PDO::PARAM_STR);
            $imagen->execute();
            $countImagen = $imagen->fetchColumn();

            if ($countImagen > 0) 
                return "imagen_almacenada";
            

            // Preparar la consulta SQL de actualización
            $sql = "UPDATE " . TABLA_OBRAS . " SET titulo = :titulo, autor = :autor, anio = :anio, categoria = :categoria, ruta_imagen = :ruta_imagen WHERE titulo = :titulo_original";
            $st = $conexion->prepare($sql);

            // Asignar valores a los marcadores de parámetros
            $st->bindValue(":titulo_original", $datos["titulo_original"], PDO::PARAM_STR);
            $st->bindValue(":titulo", $datos["titulo"], PDO::PARAM_STR);
            $st->bindValue(":ruta_imagen", $datos["ruta_imagen"], PDO::PARAM_STR);
            $st->bindValue(":autor", $datos["autor"], PDO::PARAM_STR);
            $st->bindValue(":anio", $datos["anio"], PDO::PARAM_STR);
            $st->bindValue(":categoria", $datos["categoria"], PDO::PARAM_STR);

            // Ejecutar la consulta de actualización
            $resultado = $st->execute();

            // Cerrar la conexión
            parent::desconectar($conexion);

            return $resultado;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            // Manejar errores
            die("NO SE PUDO MODIFICAR LA OBRA: " . $e->getMessage());
        } 
    }

    // Función para eliminar un USUARIO según el CORREO dado
    public static function eliminarObra($titulo) {
        $conexion = parent::conectar();
        
        $sql = "DELETE FROM " . TABLA_OBRAS . " WHERE titulo = :titulo";
        
        try {
            $st = $conexion->prepare($sql); // Preparamos la consulta
            $st->bindValue(":titulo", $titulo, PDO::PARAM_STR); // Asignar valores
            $st->execute();
                       
            parent::desconectar($conexion);
    
            // Obtener el número de filas afectadas
            // Verificar si se eliminó la obra correctamente
            return $st->rowCount() > 0; //True si se ha eliminado, False si no
        } 
        
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("ELIMINACIÓN DE LA OBRA FALLIDA: " . $e->getMessage());
        }
    }
    

    // Esta función comprueba si una ruta de imagen existe, la ruta debe ser absoluta
    public static function get_imagen($ruta) {
        return file_exists($ruta);
    }

    // Obtener todos los valores en la columna especificada
    public static function obtenerColumna($columna) {
        $conexion = parent::conectar();
        $sql = "SELECT DISTINCT $columna FROM " . TABLA_OBRAS;
    
        try {
            $st = $conexion->prepare($sql);
            $st->execute();
            $valores = array();
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $fila) {
                $valores[] = $fila[$columna];
            }
            parent::desconectar($conexion);
            return $valores;
        } 
        catch (PDOException $e) {
            parent::desconectar($conexion);
            die("Consulta para obtener '$columna' fallida: " . $e->getMessage());
        }
    }
    
}
?>  