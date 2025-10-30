<?php
// Determinar si el usuario es admin (asume que session_start() se llamó antes)
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<header class="navbar" id="main-navbar">
    <div class="container navbar-content">
        <a href="<?php echo BASE_URL; ?>" class="logo">Carobotic</a>
        <nav class="nav-links">
            <a href="<?php echo BASE_URL; ?>">Inicio</a>
            <?php if ($is_admin): ?>
                <a href="<?php echo BASE_URL; ?>admin/dashboard.php">Panel Admin</a>
                <a href="<?php echo BASE_URL; ?>admin/logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>login.php">Login Admin</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    // JS para ocultar el navbar al hacer scroll (QoL)
    let lastScrollTop = 0;
    const navbar = document.getElementById('main-navbar');

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > 50) {
            // Bajando y ya pasó los 50px
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Subiendo o en la parte superior
            navbar.style.transform = 'translateY(0)';
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Para dispositivos móviles
    }, false);

    // Ajuste CSS para la transición (Añadir a style.css)
    // .navbar { transition: transform 0.3s ease-in-out; position: fixed; width: 100%; top: 0; z-index: 100; }
</script>