<?php
// Teacher Panel Database Configuration
// This should ideally point to the same database as the admin panel and install.php
define('DB_HOST_TEACHER', 'localhost');
define('DB_NAME_TEACHER', 'school_website_db');
define('DB_USER_TEACHER', 'root'); // Replace with your DB user if different, but likely same
define('DB_PASS_TEACHER', '');     // Replace with your DB password

// Function to establish database connection for teacher panel
function get_teacher_db_connection() {
    $conn = new mysqli(DB_HOST_TEACHER, DB_USER_TEACHER, DB_PASS_TEACHER, DB_NAME_TEACHER);
    if ($conn->connect_error) {
        // In a real application, log this error and show a more user-friendly message
        die("数据库连接失败 (Teacher Panel): " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>
