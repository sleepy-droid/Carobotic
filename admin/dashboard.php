<?php
require_once '../includes/auth_check.php'; // 1. Proteger
require_once '../includes/db.php'; // 2. Conexión BD

// Obtener todas las entradas del blog
try {
    $stmt = $pdo->query("SELECT id, title, created_at FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar las entradas: " . $e->getMessage();
    $posts = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Carobotic</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .admin-table th, .admin-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .admin-table th {
            background-color: var(--color-navy);
            color: white;
        }
        .admin-table tr:nth-child(even) {
            background-color: var(--color-light-gray);
        }
        .action-link {
            color: var(--color-navy);
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        .new-post-button {
            display: inline-block;
            background-color: var(--color-navy);
            color: var(--color-white);
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
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
        <h1>Panel de Administración</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <a href="create_post.php" class="new-post-button">Crear Nueva Entrada</a>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['id']); ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                            <td>
                                <a href="../post.php?id=<?php echo $post['id']; ?>" class="action-link" target="_blank">Ver</a>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="action-link">Editar</a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="action-link" onclick="return confirm('¿Estás seguro de que quieres eliminar esta entrada?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay entradas de blog publicadas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>