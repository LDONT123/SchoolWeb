<?php
session_start();
// Determine the current page to apply active class or for auth check
$current_page = basename($_SERVER['PHP_SELF']);
$auth_required_pages = [
    'dashboard.php',
    'manage_tokens.php',
    'token_logs.php',
    'manage_faculty.php',
    'add_faculty.php',
    'edit_faculty.php',
    'delete_faculty.php',
    'manage_history.php',
    'add_history.php',
    'edit_history.php',
    'delete_history.php',
    'manage_news.php', // New
    'add_news.php',    // New
    'edit_news.php',   // New
    'delete_news.php'  // New
];

if (in_array($current_page, $auth_required_pages)) {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理后台<?php if (isset($page_title)) echo " - " . htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-header-container">
            <div class="admin-logo">
                <img src="https://ldont.pgrm.top/img/cdfz.png" alt="昆明市盘龙区财大附中校徽" style="height: 40px; width: auto; margin-right: 10px; vertical-align: middle;">
                <h1>管理后台</h1>
            </div>
            <nav class="admin-nav">
                <ul>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">仪表盘</a></li>
                        <li><a href="manage_faculty.php" class="<?php echo (in_array($current_page, ['manage_faculty.php', 'add_faculty.php', 'edit_faculty.php'])) ? 'active' : ''; ?>">教职工管理</a></li>
                        <li><a href="manage_history.php" class="<?php echo (in_array($current_page, ['manage_history.php', 'add_history.php', 'edit_history.php'])) ? 'active' : ''; ?>">校史管理</a></li>
                        <li><a href="manage_news.php" class="<?php echo (in_array($current_page, ['manage_news.php', 'add_news.php', 'edit_news.php'])) ? 'active' : ''; ?>">新闻管理</a></li>
                        <li><a href="manage_tokens.php" class="<?php echo ($current_page == 'manage_tokens.php') ? 'active' : ''; ?>">Token管理</a></li>
                        <li><a href="token_logs.php" class="<?php echo ($current_page == 'token_logs.php') ? 'active' : ''; ?>">Token日志</a></li>
                        <li><a href="logout.php">登出</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="admin-main">
        <div class="admin-container">
        <!-- Content of specific admin pages will go here -->
