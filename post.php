<?php
require_once 'includes/db.php';
require_once 'includes/config.php';
session_start();

// 1. Obtener el ID de la entrada de la URL (Query string)
$post_id = $_GET['id'] ?? null;
$post = null;
$error = '';

if ($post_id) {
    try {
        // 2. Preparar y ejecutar la consulta segura para obtener la entrada
        $stmt = $pdo->prepare("SELECT id, title, content, image_url, created_at FROM posts WHERE id = :id");
        $stmt->execute(['id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            $error = "La entrada de blog solicitada no existe.";
        }
    } catch (PDOException $e) {
        $error = "Error al conectar con la base de datos.";
        // error_log($e->getMessage());
    }
} else {
    $error = "ID de entrada no especificado.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? htmlspecialchars($post['title']) : 'Entrada no encontrada'; ?> | Carobotic</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .post-detail {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
        }
        .post-detail h1 {
            color: var(--color-navy);
            margin-bottom: 10px;
        }
        .post-meta {
            color: gray;
            font-style: italic;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--color-light-gray);
            padding-bottom: 10px;
        }
        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .post-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; // Navbar modular ?>

    <main class="container">
        <section class="post-detail">
            <?php if ($post): ?>
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="post-meta">Publicado el: <?php echo date('d M, Y', strtotime($post['created_at'])); ?></p>
                
                <img src="<?php echo BASE_URL; ?>uploads/<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">
                
                <div class="post-content">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
            <?php else: ?>
                <h1 style="color: red;">Error: <?php echo $error; ?></h1>
                <p>Lo sentimos, la entrada que buscas no está disponible.</p>
                <a href="<?php echo BASE_URL; ?>" style="color: var(--color-navy); font-weight: bold;">Volver a la página principal</a>
            <?php endif; ?>
        </section>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Carobotic. Innovación en Vehículos Autónomos.</p>
            <p style="font-size: 0.9em;">Desarrollado con PHP Puro.</p>
        </div>
    </footer>

</body>
</html>