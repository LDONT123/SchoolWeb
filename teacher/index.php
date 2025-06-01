<?php
session_start(); // Start session at the very beginning
$page_title = "教师登录";

// If already logged in, redirect to panel
if (isset($_SESSION['teacher_logged_in']) && $_SESSION['teacher_logged_in'] === true) {
    header("Location: panel.php");
    exit;
}

$error_message = '';
require_once 'config.php'; // Use teacher's config file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted_token = trim($_POST['token']);
    $teacher_name = trim($_POST['teacher_name']);

    if (empty($submitted_token) || empty($teacher_name)) {
        $error_message = "请输入Token和您的姓名。";
    } else {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($conn->connect_error) {
            $error_message = "数据库连接失败。请稍后重试。";
        } else {
            $conn->set_charset("utf8mb4");

            $sql_check_token = "SELECT id, status FROM tokens WHERE token_value = ? AND status = 'active'";
            $stmt_check = $conn->prepare($sql_check_token);

            if ($stmt_check) {
                $stmt_check->bind_param("s", $submitted_token);
                $stmt_check->execute();
                $result_token = $stmt_check->get_result();

                if ($result_token->num_rows === 1) {
                    $token_data = $result_token->fetch_assoc();

                    $_SESSION['teacher_logged_in'] = true;
                    $_SESSION['teacher_token'] = $submitted_token;
                    $_SESSION['teacher_name'] = htmlspecialchars($teacher_name);

                    $action_taken = "教师登录成功";
                    $stmt_log = $conn->prepare("INSERT INTO token_logs (token_value, teacher_name_entered, action_taken) VALUES (?, ?, ?)");
                    if ($stmt_log) {
                        $stmt_log->bind_param("sss", $submitted_token, $teacher_name, $action_taken);
                        $stmt_log->execute();
                        $stmt_log->close();
                    }

                    $stmt_update_token = $conn->prepare("UPDATE tokens SET last_used_at = NOW(), last_used_by_name = ? WHERE token_value = ?");
                    if ($stmt_update_token) {
                        $stmt_update_token->bind_param("ss", $teacher_name, $submitted_token);
                        $stmt_update_token->execute();
                        $stmt_update_token->close();
                    }

                    header("Location: panel.php");
                    exit;

                } else {
                    $error_message = "无效或已停用的Token。";
                    $action_taken_failed = "教师登录失败：无效或停用Token (" . $submitted_token . ")";
                    $stmt_log_failed = $conn->prepare("INSERT INTO token_logs (token_value, teacher_name_entered, action_taken) VALUES (?, ?, ?)");
                    if ($stmt_log_failed) {
                        $stmt_log_failed->bind_param("sss", $submitted_token, $teacher_name, $action_taken_failed);
                        $stmt_log_failed->execute();
                        $stmt_log_failed->close();
                    }
                }
                $stmt_check->close();
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
    <title><?php echo htmlspecialchars($page_title); ?> - 教师面板 - 昆明市盘龙区财大附中</title>
    <link rel="stylesheet" href="css/teacher_style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h1>教师登录</h1>
        <p style="text-align: center; margin-bottom: 20px;">昆明市盘龙区财大附中</p>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="token">Token:</label>
                <input type="text" id="token" name="token" required value="<?php if(isset($_POST['token'])) echo htmlspecialchars($_POST['token']); ?>">
            </div>
            <div class="form-group">
                <label for="teacher_name">您的姓名:</label>
                <input type="text" id="teacher_name" name="teacher_name" required placeholder="请输入真实姓名以便记录" value="<?php if(isset($_POST['teacher_name'])) echo htmlspecialchars($_POST['teacher_name']); ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="登录">
            </div>
        </form>
         <p class="info-text">请使用管理员生成的有效Token进行登录。</p>
    </div>
</body>
</html>
