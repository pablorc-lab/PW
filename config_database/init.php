<?php
require_once 'configuracion.inc.php';

// Establecer conexión a la base de datos
try{
    $conexion = new PDO(DB_DSN, DB_USUARIO, DB_CONTRASENIA);
} 
catch (PDOException $e){
    die("Conexión fallida: " . $e->getMessage() );
}

// Ejecutar consulta SQL para crear la tabla Usuarios si no existe
$sql = "CREATE TABLE IF NOT EXISTS Usuarios (
    correo VARCHAR(50) PRIMARY KEY,
    contrasenia VARCHAR(50),
    nombre VARCHAR(50),
    apellidos VARCHAR(50),
    pais VARCHAR(50),
    ciudad VARCHAR(50),
    telefono INTEGER UNIQUE,
    fechaNacimiento DATE,
    tipo VARCHAR(20)
)"; 
$conexion->exec($sql);


// Ejecutar consulta SQL para crear la tabla Obras si no existe
$sql = "CREATE TABLE IF NOT EXISTS Obras (
    titulo VARCHAR(50) PRIMARY KEY,
    autor VARCHAR(100),
    anio VARCHAR(50),
    categoria VARCHAR(50),
    ruta_imagen VARCHAR(110) UNIQUE
)";
$conexion->exec($sql);

// Crear usuario administrador
/*
$sql = "INSERT INTO Usuarios (correo, contrasenia, nombre, apellidos, pais, ciudad, telefono, fechaNacimiento, tipo) 
        VALUES ('admin@example.com', 'adminpassword', 'Admin', 'AdminLastName', 'PaisAdmin', 'CiudadAdmin', 123456789, '1980-01-01', 'Admin')";
$conexion->exec($sql);
*/

// Cremaos la tabla para almacenar los Comentarios
$sql = "CREATE TABLE IF NOT EXISTS Comentarios (
    correo VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(100),
    valoracion INTEGER,
    comentario TEXT,
    FOREIGN KEY (correo) REFERENCES Usuarios(correo)
)";
$conexion->exec($sql);

echo "<h1>Tabla USUARIOS, OBRAS y COMENTARIOS creadas (si no lo estaban) correctamente</h1>"
?>
