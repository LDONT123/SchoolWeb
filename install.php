<?php
// Enable error reporting for debugging (remove or adjust for production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$messages = []; // To store success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $db_host = trim($_POST['db_host']);
    $db_name = trim($_POST['db_name']);
    $db_user = trim($_POST['db_user']);
    $db_pass = trim($_POST['db_pass']);
    $admin_user = trim($_POST['admin_user']);
    $admin_pass = trim($_POST['admin_pass']);

    // Validate basic input
    if (empty($db_host) || empty($db_name) || empty($db_user) || empty($admin_user) || empty($admin_pass)) {
        $messages[] = ['type' => 'error', 'text' => '错误：所有必填字段（数据库主机、名称、用户、管理员用户名、管理员密码）都必须填写。'];
    } else {
        // 1. Connect to MySQL server
        $conn = new mysqli($db_host, $db_user, $db_pass);

        if ($conn->connect_error) {
            $messages[] = ['type' => 'error', 'text' => '数据库连接失败： ' . $conn->connect_error];
        } else {
            $messages[] = ['type' => 'success', 'text' => '成功连接到MySQL服务器。'];

            // 2. Create database if it doesn't exist
            $sql_create_db = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if ($conn->query($sql_create_db) === TRUE) {
                $messages[] = ['type' => 'success', 'text' => "数据库 '$db_name' 创建成功或已存在。"];

                // 3. Select the database
                if ($conn->select_db($db_name)) {
                    $messages[] = ['type' => 'success', 'text' => "已选择数据库 '$db_name'。"];

                    // 4. Table Creation SQL statements
                    $sql_admin_users = "CREATE TABLE IF NOT EXISTS `admin_users` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `username` VARCHAR(255) UNIQUE NOT NULL,
                        `password_hash` VARCHAR(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $sql_tokens = "CREATE TABLE IF NOT EXISTS `tokens` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `token_value` VARCHAR(255) UNIQUE NOT NULL,
                        `status` VARCHAR(50) NOT NULL DEFAULT 'active',
                        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `last_used_at` TIMESTAMP NULL DEFAULT NULL,
                        `last_used_by_name` VARCHAR(255) NULL DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $sql_faculty = "CREATE TABLE IF NOT EXISTS `faculty` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `name` VARCHAR(255) NOT NULL,
                        `title` VARCHAR(255) NULL,
                        `bio` TEXT NULL,
                        `image_path` VARCHAR(255) NULL,
                        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $sql_school_history = "CREATE TABLE IF NOT EXISTS `school_history` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `event_date` VARCHAR(100) NULL,
                        `title` VARCHAR(255) NOT NULL,
                        `description` TEXT NULL,
                        `multimedia_path` VARCHAR(255) NULL,
                        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $sql_campus_news = "CREATE TABLE IF NOT EXISTS `campus_news` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `title` VARCHAR(255) NOT NULL,
                        `content` TEXT NOT NULL,
                        `author_name` VARCHAR(255) NULL,
                        `token_used` VARCHAR(255) NULL,
                        `publication_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `multimedia_path` VARCHAR(255) NULL,
                        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $sql_token_logs = "CREATE TABLE IF NOT EXISTS `token_logs` (
                        `id` INT PRIMARY KEY AUTO_INCREMENT,
                        `token_value` VARCHAR(255) NOT NULL,
                        `teacher_name_entered` VARCHAR(255) NOT NULL,
                        `action_taken` TEXT NOT NULL,
                        `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                    $tables_sql = [
                        'admin_users' => $sql_admin_users,
                        'tokens' => $sql_tokens,
                        'faculty' => $sql_faculty,
                        'school_history' => $sql_school_history,
                        'campus_news' => $sql_campus_news,
                        'token_logs' => $sql_token_logs
                    ];

                    $all_tables_created = true;
                    foreach ($tables_sql as $table_name => $sql) {
                        if ($conn->query($sql) === TRUE) {
                            $messages[] = ['type' => 'success', 'text' => "数据表 '$table_name' 创建成功或已存在。"];
                        } else {
                            $messages[] = ['type' => 'error', 'text' => "创建数据表 '$table_name' 失败: " . $conn->error];
                            $all_tables_created = false;
                        }
                    }

                    if ($all_tables_created) {
                        // 5. Admin User Creation
                        // Check if admin user already exists
                        $stmt_check_admin = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
                        $stmt_check_admin->bind_param("s", $admin_user);
                        $stmt_check_admin->execute();
                        $stmt_check_admin->store_result();

                        if ($stmt_check_admin->num_rows === 0) {
                            $admin_pass_hash = password_hash($admin_pass, PASSWORD_DEFAULT);
                            $stmt_insert_admin = $conn->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
                            $stmt_insert_admin->bind_param("ss", $admin_user, $admin_pass_hash);

                            if ($stmt_insert_admin->execute()) {
                                $messages[] = ['type' => 'success', 'text' => "管理员用户 '$admin_user' 创建成功。"];
                                $messages[] = ['type' => 'important', 'text' => '安装过程已完成！请务必在部署后删除或保护此 install.php 文件。'];
                            } else {
                                $messages[] = ['type' => 'error', 'text' => "创建管理员用户 '$admin_user' 失败: " . $stmt_insert_admin->error];
                            }
                            $stmt_insert_admin->close();
                        } else {
                            $messages[] = ['type' => 'info', 'text' => "管理员用户 '$admin_user' 已存在，跳过创建。"];
                             $messages[] = ['type' => 'important', 'text' => '安装似乎已完成或部分完成。请检查以上消息。如果需要重新安装，请清空数据库并重试。请务必在部署后删除或保护此 install.php 文件。'];
                        }
                        $stmt_check_admin->close();
                    }

                } else {
                    $messages[] = ['type' => 'error', 'text' => "选择数据库 '$db_name' 失败: " . $conn->error];
                }
            } else {
                $messages[] = ['type' => 'error', 'text' => "创建数据库 '$db_name' 失败: " . $conn->error];
            }
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网站安装程序</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h1 { color: #333; text-align: center; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="submit"] {
            width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;
        }
        input[type="submit"] { background-color: #5cb85c; color: white; font-size: 16px; cursor: pointer; border: none; }
        input[type="submit"]:hover { background-color: #4cae4c; }
        .messages div { padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .messages .success { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .messages .error { background-color: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        .messages .info { background-color: #d9edf7; color: #31708f; border: 1px solid #bce8f1; }
        .messages .important { background-color: #fcf8e3; color: #8a6d3b; border: 1px solid #faebcc; font-weight: bold;}
    </style>
</head>
<body>
    <div class="container">
        <h1>网站安装与配置</h1>

        <?php if (!empty($messages)): ?>
            <div class="messages">
                <?php foreach ($messages as $message): ?>
                    <div class="<?php echo htmlspecialchars($message['type']); ?>">
                        <?php echo htmlspecialchars($message['text']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="install.php" method="post">
            <h2>数据库设置</h2>
            <div>
                <label for="db_host">数据库主机:</label>
                <input type="text" id="db_host" name="db_host" value="localhost" required>
            </div>
            <div>
                <label for="db_name">数据库名称:</label>
                <input type="text" id="db_name" name="db_name" value="school_website_db" required>
            </div>
            <div>
                <label for="db_user">数据库用户名:</label>
                <input type="text" id="db_user" name="db_user" required>
            </div>
            <div>
                <label for="db_pass">数据库密码:</label>
                <input type="password" id="db_pass" name="db_pass">
            </div>

            <h2>管理员账户设置</h2>
            <div>
                <label for="admin_user">管理员用户名:</label>
                <input type="text" id="admin_user" name="admin_user" value="admin" required>
            </div>
            <div>
                <label for="admin_pass">管理员密码:</label>
                <input type="password" id="admin_pass" name="admin_pass" required>
            </div>

            <div>
                <input type="submit" value="开始安装">
            </div>
        </form>
    </div>
</body>
</html>
