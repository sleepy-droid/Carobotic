<?php
// Asegúrate de que las rutas relativas funcionen correctamente:
// Sale de /admin para ir a /includes
require_once '../includes/auth_check.php'; 
require_once '../includes/db.php';
require_once '../includes/config.php';

$id = $_GET['id'] ?? null;
$post = null;
$message = '';
$errors = [];

if (!$id) {
    header('Location: dashboard.php');
    exit;
}

// 1. Obtener la entrada existente para precargar el formulario
try {
    $stmt = $pdo->prepare("SELECT id, title, content, image_url FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        // Si el post no existe, redirigir
        header('Location: dashboard.php');
        exit;
    }
} catch (PDOException $e) {
    $errors[] = "Error al cargar la entrada para edición.";
}

// 2. Procesar el envío del formulario (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $post) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $current_image_url = $post['image_url']; // Mantener la imagen actual por defecto

    if (empty($title)) $errors[] = "El título es obligatorio.";
    if (empty($content)) $errors[] = "El contenido es obligatorio.";

    // Procesamiento de nueva imagen si se sube una
    if (empty($errors) && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = '../uploads/';
            $new_image_url = uniqid() . '-' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $new_image_url;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // 1. Actualizar la variable para la BD
                $current_image_url = $new_image_url;
                // 2. Eliminar la imagen antigua del servidor (QoL)
                $old_file = $upload_dir . $post['image_url'];
                if (file_exists($old_file) && is_writable($old_file)) {
                    unlink($old_file);
                }
            } else {
                $errors[] = "Hubo un error al subir la nueva imagen.";
            }
        } else {
            $errors[] = "Tipo de archivo de imagen no permitido.";
        }
    }

    // 3. Ejecutar la actualización en la BD
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content, image_url = :image_url WHERE id = :id");
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'image_url' => $current_image_url,
                'id' => $id
            ]);

            $message = "¡Entrada actualizada exitosamente!";
            
            // Recargar los datos del post después de la actualización
            $post['title'] = $title;
            $post['content'] = $content;
            $post['image_url'] = $current_image_url;

        } catch (PDOException $e) {
            $errors[] = "Error de base de datos al actualizar la entrada: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Entrada - Carobotic</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .post-form { max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid var(--color-light-gray); border-radius: 8px; }
        .post-form label { display: block; margin-top: 15px; font-weight: bold; }
        .post-form input[type="text"], .post-form textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .post-form textarea { resize: vertical; min-height: 300px; }
        .post-form button { background-color: var(--color-navy); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; }
        .error-list { color: red; list-style: none; padding-left: 0; margin-bottom: 20px; }
        .success-message { color: green; font-weight: bold; margin-bottom: 20px; }
        .current-image { margin-top: 15px; }
        .current-image img { max-width: 200px; height: auto; display: block; margin-top: 10px; border: 1px solid #ccc;}
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <main class="container">
        <h1>Editar Entrada: <?php echo htmlspecialchars($post['title'] ?? 'Cargando...'); ?></h1>

        <?php if (!empty($errors)): ?>
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li>- <?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($message): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($post): ?>
            <form action="edit_post.php?id=<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data" class="post-form">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? $post['title']); ?>" required>
                
                <label for="content">Contenido:</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($content ?? $post['content']); ?></textarea>
                
                <div class="current-image">
                    <label>Imagen Actual:</label>
                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo htmlspecialchars($post['image_url']); ?>" alt="Imagen actual">
                </div>

                <label for="image">Reemplazar Imagen de Portada (Opcional):</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">

                <button type="submit">Actualizar Entrada</button>
                <a href="dashboard.php" style="margin-left: 20px;" class="action-link">Volver al Dashboard</a>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>