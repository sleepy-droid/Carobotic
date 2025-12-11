# Project Overview

This project is a simple blog application named "Carobotic" focused on autonomous vehicles. It is built with pure PHP and uses a MySQL database. The application has a public-facing side for viewing blog posts and an admin panel for managing them.

## Key Technologies

*   **Backend:** PHP
*   **Database:** MySQL
*   **Frontend:** HTML, CSS, JavaScript

# Building and Running the Project

This project is designed to run on a standard web server with PHP and MySQL, such as XAMPP, WAMP, or MAMP.

1.  **Database Setup:**
    *   Create a new database named `carobotic_blog` in your MySQL server.
    *   Import the database schema from the `sql/carobotic_blog_schema.sql` file. This will create the `posts` and `users` tables.

2.  **Configuration:**
    *   In the `includes/` directory, create a copy of `db.sample.php` and rename it to `db.php`.
    *   Open `db.php` and update the database credentials (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) to match your local environment.

3.  **Running the Application:**
    *   Place the project files in the web root directory of your server (e.g., `htdocs` for XAMPP).
    *   Open your web browser and navigate to the project's URL (e.g., `http://localhost/Carobotic/`).

# Admin Panel

The application includes an admin panel for managing blog posts.

*   **Admin Access:** To access the admin panel, you first need to register a user.
    *   Navigate to `register.php` to create the first admin user. **Note:** The application only allows one user to be registered.
    *   Once registered, you can log in at `login.php`.
*   **Functionality:** The admin panel, located at `admin/dashboard.php`, allows you to:
    *   Create new blog posts.
    *   Edit existing blog posts.
    *   Delete blog posts.

# Development Conventions

*   The code is written in procedural PHP.
*   Database interactions are done using the PDO (PHP Data Objects) extension for security against SQL injection.
*   The project structure separates concerns into different folders:
    *   `admin/`: Admin panel files
    *   `css/`: Stylesheets
    *   `includes/`: Reusable components like database connection and authentication checks
    *   `js/`: Client-side scripts
    *   `sql/`: Database schema
    *   `uploads/`: Directory for uploaded post images
*   Sensitive information like database credentials is not meant to be committed to version control, as indicated by the `db.sample.php` file.
