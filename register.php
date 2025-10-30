<?php
require_once 'includes/config.php';
require_once 'includes/db.php'; // Incluye la conexión PDO

// 1. Verificar si ya hay usuarios registrados
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $user_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error de base de datos inicial: " . $e->getMessage());
}

// Si ya existe al menos un usuario, no permitimos el registro
if ($user_count > 0) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($password) || empty($password_confirm)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // 2. Hashear la contraseña de forma segura
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Insertar el nuevo usuario
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $hashed_password]);
            
            // Éxito: Redirigir al login
            header('Location: ' . BASE_URL . 'login.php?registered=true');
            exit;
            
        } catch (PDOException $e) {
            $error = "Error al intentar registrar el usuario. El nombre de usuario puede ya estar en uso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Admin - Carobotic</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .register-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            border: 2px solid var(--color-navy);
            border-radius: 10px;
            text-align: center;
        }
        /* ... (Estilos de input/button iguales al login) ... */
        .register-box input[type="text"], .register-box input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid var(--color-light-gray);
            border-radius: 5px;
            display: block;
        }
        .register-box button {
            background-color: var(--color-navy);
            color: var(--color-white);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; // Navbar para QoL ?>
    
    <div class="register-box">
        <h2>Registro de Primer Administrador</h2>
        <p>Solo puedes registrarte si no existe un usuario en el sistema.</p>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Nuevo Usuario" required>
            <input type="password" name="password" placeholder="Contraseña (Mín. 8 chars)" required>
            <input type="password" name="password_confirm" placeholder="Repetir Contraseña" required>
            <button type="submit">Registrar y Continuar</button>
        </form>
    </div>
</body>
</html>