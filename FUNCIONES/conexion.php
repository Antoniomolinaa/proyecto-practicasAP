<?php
// FUNCIONES/conexion.php


function conectar() {
    $host = "localhost";
    $db   = "practicas";
    $user = "practicas";
    $pass = "vKj7y2k8Nb23aA6!"; 
    $charset = "utf8mb4";

    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        // Usamos los números internos de PDO para evitar el error de constante no definida
        $options = [
            3 => 2, // PDO::ATTR_ERRMODE => PDO::ATTR_ERRMODE_EXCEPTION
            19 => 2, // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            20 => false, // PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        return new PDO($dsn, $user, $pass, $options);
        
    } catch (Exception $e) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}
?>