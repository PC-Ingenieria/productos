<?php

class Conexion {
    private static $host = "localhost";
    private static $db_name = "productos"; 
    private static $username = "root";     
    private static $password = "";         
    private static $pdo = null;

    public static function conectar() {
        // Si ya existe una conexión activa, la reutiliza en lugar de crear una nueva
        if (self::$pdo != null) {
            return self::$pdo;
        }

        try {
            // Configuración del DSN (Data Source Name)
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4";
            
            // Opciones avanzadas de configuración para PDO
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activa el manejo de excepciones para errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos como arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para mejorar la seguridad contra SQL Injection
            ];

            // Instancia de PDO
            self::$pdo = new PDO($dsn, self::$username, self::$password, $opciones);
            return self::$pdo;

        } catch (PDOException $e) {
            // Si hay un error, detiene el script y muestra el mensaje (puedes cambiarlo en producción para no dar pistas)
            die("Error crítico de conexión a la Base de Datos: " . $e->getMessage());
        }
    }
}