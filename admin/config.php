<?php
// Admin Panel Database Configuration
// Replace with your actual database credentials used during install.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_website_db');
define('DB_USER', 'root'); // Replace with your DB user
define('DB_PASS', '');     // Replace with your DB password

// Function to establish database connection
function get_db_connection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        // In a real application, log this error and show a more user-friendly message
        // For admin panel, showing the error might be acceptable during development
        die("数据库连接失败 (Admin Panel): " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>
