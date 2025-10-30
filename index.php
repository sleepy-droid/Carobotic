<?php
require_once 'includes/db.php';
require_once 'includes/config.php';
session_start();

// 1. Obtener las 2 entradas más recientes para el carrusel (el más reciente primero)
try {
    $stmt = $pdo->prepare("SELECT id, title, content, image_url FROM posts ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En caso de error de BD
    $posts = []; 
    // Loguear error: error_log($e->getMessage());
}

$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carobotic - Blog de Vehículos Autónomos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php
    include 'includes/navbar.php';
    ?>

    <main class="container">
        <section class="carousel-container">
            <div class="carousel-slide" id="blog-carousel">
                <?php if (count($posts) > 0): ?>
                    <?php foreach ($posts as $index => $post): ?>
                        <div class="carousel-item" style="background-image: url('uploads/<?php echo htmlspecialchars($post['image_url']); ?>');">
                            <img src="uploads/<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <div class="carousel-caption">
                                <h2><a href="post.php?id=<?php echo $post['id']; ?>" style="color:white; text-decoration:none;"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                                <p><?php echo substr(htmlspecialchars($post['content']), 0, 100) . '...'; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item" style="width:100%; background: var(--color-navy);"> 
                        <div class="carousel-caption">
                            <h2>¡Bienvenido a Carobotic!</h2>
                            <p>Aún no hay entradas de blog publicadas. Inicia sesión como Admin para crear una.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <button id="prev-btn" class="carousel-nav-btn">❮</button>
            <button id="next-btn" class="carousel-nav-btn">❯</button>

            <div class="carousel-indicators" id="carousel-indicators"></div>
        </section>

        <section class="blog-list">
            <h1>Últimas Entradas</h1>
            <div class="posts-grid" style="display: flex; gap: 20px;">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
    <div class="post-card-thumb">
        <img src="uploads/<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
    </div>
    <div class="post-card-content">
        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
        <p><?php echo substr(htmlspecialchars($post['content']), 0, 80) . '...'; ?></p>
        <a href="<?php echo BASE_URL; ?>post.php?id=<?php echo $post['id']; ?>">Leer más</a>
    </div>
</article>
                <?php endforeach; ?>
            </div>
        </section>

    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Carobotic. Innovación en Vehículos Autónomos.</p>
            <p style="font-size: 0.9em;">Desarrollado con PHP Puro.</p>
        </div>
    </footer>

    <script src="js/carousel.js"></script>
</body>
</html>