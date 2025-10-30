<?php
// Inicia la sesión
session_start();

// Si el administrador no está logueado, lo redirige al formulario de login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // La redirección usa "../login.php" porque estamos saliendo de la carpeta /admin
    header('Location: ../login.php');
    exit;
}
// Si está logueado, el script continúa con normalidad.
?>