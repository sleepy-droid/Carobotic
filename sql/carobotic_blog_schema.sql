-- Estructura de la base de datos para Carobotic Blog

CREATE DATABASE IF NOT EXISTS carobotic_blog;
USE carobotic_blog;

-- Tabla de Entradas del Blog
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Usuarios (Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    -- El password debe ser de 255 para guardar el hash seguro
    password VARCHAR(255) NOT NULL 
);
-- NOTA: El admin debe insertar el hash de la contraseña manualmente 
-- despues de crear la BD. Usar password_hash() en PHP.