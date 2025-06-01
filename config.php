<?php
// Main Website Database Configuration
define('DB_HOST_MAIN', 'localhost');
define('DB_NAME_MAIN', 'school_website_db');
define('DB_USER_MAIN', 'root'); // Replace with your DB user
define('DB_PASS_MAIN', '');     // Replace with your DB password

// Function to establish database connection for the main website
function get_main_db_connection() {
    $conn = new mysqli(DB_HOST_MAIN, DB_USER_MAIN, DB_PASS_MAIN, DB_NAME_MAIN);
    if ($conn->connect_error) {
        // For a public site, a generic error is better than exposing DB details.
        // Log the actual error to a server file.
        error_log("Main DB Connection Error: " . $conn->connect_error);
        die("网站暂时无法访问，请稍后再试。 (Error connecting to database)");
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Define items per page for pagination
define('NEWS_PER_PAGE', 10);
?>
