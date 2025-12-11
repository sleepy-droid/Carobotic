<?php
// api/get_post.php

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

// 1. Obtener el ID de la entrada de la URL
$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de entrada no especificado.'
    ]);
    exit;
}

try {
    // 2. Preparar y ejecutar la consulta segura para obtener la entrada
    $stmt = $pdo->prepare("SELECT id, title, content, image_url, created_at FROM posts WHERE id = :id");
    $stmt->execute(['id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // 3. Construir la URL completa para la imagen
        $post['image_url'] = BASE_URL . 'uploads/' . $post['image_url'];

        // 4. Devolver la entrada en formato JSON
        echo json_encode([
            'status' => 'success',
            'data' => $post
        ]);
    } else {
        // Si no se encuentra la entrada, devolver un error 404
        http_response_code(404); // Not Found
        echo json_encode([
            'status' => 'error',
            'message' => 'La entrada de blog solicitada no existe.'
        ]);
    }

} catch (PDOException $e) {
    // En caso de error de la base de datos
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()
    ]);
}
?>