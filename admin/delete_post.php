<?php
// Asegúrate de que las rutas relativas funcionen correctamente:
// Sale de /admin para ir a /includes
require_once '../includes/auth_check.php'; 
require_once '../includes/db.php';
require_once '../includes/config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: dashboard.php');
    exit;
}

try {
    // 1. Obtener la ruta de la imagen antes de eliminar el registro
    $stmt = $pdo->prepare("SELECT image_url FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // 2. Eliminar el registro de la base de datos
        $stmt_delete = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt_delete->execute(['id' => $id]);

        // 3. Eliminar la imagen física del servidor (QoL: unlink)
        // La ruta sale de /admin, por eso usamos '../uploads/'
        $file_to_delete = '../uploads/' . $post['image_url']; 
        
        if (file_exists($file_to_delete) && is_writable($file_to_delete)) {
            unlink($file_to_delete);
        }
        
        // Redirigir al dashboard con mensaje de éxito
        header('Location: dashboard.php?deleted=true');
        exit;
    }

} catch (PDOException $e) {
    // Si hay un error, puedes redirigir con un mensaje de error
    header('Location: dashboard.php?error=' . urlencode("Error al eliminar la entrada."));
    exit;
}

header('Location: dashboard.php');
exit;
?>