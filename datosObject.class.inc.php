<?php
require_once("config_database/configuracion.inc.php");

abstract class DataObject {
    // Valores
    protected $datos = array();

    // Contructor
    public function __construct($datos){
        foreach ($datos as $clave => $valor){
            if (array_key_exists( $clave, $this->datos )){
                $this->datos[$clave] = $valor;
            }
        }
    }

    // Devolver valor si existe
    public function getValor( $campo ){
        if (array_key_exists($campo, $this->datos )) {
            return $this->datos[$campo];
        } 
        else die( "Campo no encontrado" );
    }

    // Devolver todos los valores
    public function get_datos() {
        return $this->datos;
    }

    // Conectarse a la DB    
    protected static function conectar(){
        try{
            // Crear conexion
            $conexion = new PDO(DB_DSN, DB_USUARIO, DB_CONTRASENIA);

            // Conexion persistente
            $conexion->setAttribute( PDO::ATTR_PERSISTENT, true );

            // Mostrar excepciones durante consultas SQL
            $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
        } 
        catch (PDOException $e){
            die("Conexión fallida: " . $e->getMessage() );
        }
        
        return $conexion;
    }

    // Desconectarse de la DB    
    protected static function desconectar( $conexion ) {
        $conexion = null;
    }
}
?> 