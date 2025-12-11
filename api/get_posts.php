<?php
// api/get_posts.php

// Incluir la configuración de la base de datos
require_once '../includes/db.php';
// require_once '../includes/config.php'; // Comentado debido a que el archivo config.php es ignorado

// WORKAROUND: Definir BASE_URL directamente aquí ya que includes/config.php está siendo ignorado
// NOTA: En una configuración de producción, BASE_URL debería venir de un archivo de configuración compartido.
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/Carobotic/');
}

// Establecer la cabecera para devolver contenido JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permitir acceso desde cualquier origen (para desarrollo)

try {
    // 1. Preparar y ejecutar la consulta para obtener todas las entradas
    $stmt = $pdo->prepare("SELECT id, title, content, image_url, created_at FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Construir URLs completas para las imágenes
    $base_image_url = BASE_URL . 'uploads/';
    foreach ($posts as &$post) {
        $post['image_url'] = $base_image_url . $post['image_url'];
    }

    // 3. Devolver los resultados en formato JSON
    echo json_encode([
        'status' => 'success',
        'data' => $posts
    ]);

} catch (PDOException $e) {
    // En caso de error, devolver un JSON con el mensaje de error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
    ]);
}
?>