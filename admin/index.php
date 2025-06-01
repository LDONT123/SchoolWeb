<?php
session_start();
$page_title = "登录";

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "请输入用户名和密码。";
    } else {
        $conn = get_db_connection();

        // Connection error is handled within get_db_connection by die()
        // if ($conn->connect_error) {
        //     $error_message = "数据库连接失败。请检查您的数据库配置。错误：" . $conn->connect_error;
        // } else {
        // $conn->set_charset("utf8mb4"); // Set in get_db_connection

        $sql = "SELECT id, username, password_hash FROM admin_users WHERE username = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password_hash'])) {
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_username'] = $user['username'];
                        $_SESSION['admin_id'] = $user['id'];
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $error_message = "用户名或密码无效。";
                    }
                } else {
                    $error_message = "用户名或密码无效。";
                }
                $stmt->close();
            } else {
                $error_message = "数据库查询准备失败：" . $conn->error;
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
    <title><?php echo htmlspecialchars($page_title); ?> - 管理后台 - 昆明市盘龙区财大附中</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h1>管理后台登录</h1>
        <p style="text-align: center; margin-bottom: 20px;">昆明市盘龙区财大附中</p>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required value="<?php if(isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>">
            </div>
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="登录">
            </div>
        </form>
    </div>
</body>
</html>
