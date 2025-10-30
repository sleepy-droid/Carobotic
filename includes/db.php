<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'carobotic_blog'); // tu nombre de BD
define('DB_USER', 'root'); // tu usuario
define('DB_PASS', ''); // tu contraseña

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Solo para pruebas
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>