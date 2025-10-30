<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

$title = $content = $image_url = $message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    // 1. Validación de campos
    if (empty($title)) {
        $errors[] = "El título es obligatorio.";
    }
    if (empty($content)) {
        $errors[] = "El contenido es obligatorio.";
    }

    // 2. Procesamiento de la imagen
    if (empty($errors) && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Tipo de archivo no permitido. Solo se aceptan JPEG, PNG, GIF.";
        } else {
            $upload_dir = '../uploads/';
            // Crear un nombre de archivo único para evitar colisiones
            $image_url = uniqid() . '-' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $image_url;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $errors[] = "Hubo un error al subir la imagen.";
            }
        }
    } else if (empty($errors)) {
         $errors[] = "La imagen de portada es obligatoria.";
    }

    // 3. Inserción en la base de datos si no hay errores
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image_url) VALUES (:title, :content, :image_url)");
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'image_url' => $image_url
            ]);
            $message = "¡Entrada creada exitosamente!";
            // Limpiar los campos del formulario después del éxito (excepto el mensaje)
            $title = $content = '';
            
            // Opcional: Redirigir al dashboard
            // header('Location: dashboard.php');
            // exit;
            
        } catch (PDOException $e) {
            $errors[] = "Error de base de datos al crear la entrada: " . $e->getMessage();
            // error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Entrada - Carobotic</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .post-form {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid var(--color-light-gray);
            border-radius: 8px;
        }
        .post-form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        .post-form input[type="text"], .post-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Incluye padding en el ancho */
        }
        .post-form textarea {
            resize: vertical;
            min-height: 300px;
        }
        .post-form button {
            background-color: var(--color-navy);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .error-list {
            color: red;
            list-style: none;
            padding-left: 0;
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container navbar-content">
            <a href="../index.php" class="logo">Carobotic</a>
            <nav class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Cerrar Sesión</a>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <h1>Crear Nueva Entrada</h1>

        <?php if (!empty($errors)): ?>
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li>- <?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($message): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="create_post.php" method="POST" enctype="multipart/form-data" class="post-form">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            
            <label for="content">Contenido:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
            
            <label for="image">Imagen de Portada (JPG, PNG, GIF):</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif" required>

            <button type="submit">Publicar Entrada</button>
            <a href="dashboard.php" style="margin-left: 20px;" class="action-link">Volver al Dashboard</a>
        </form>
    </main>
</body>
</html>