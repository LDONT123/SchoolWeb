<?php
session_start();
// Determine the current page to apply active class or for auth check
$current_page = basename($_SERVER['PHP_SELF']);
$auth_required_pages = [
    'panel.php',
    'manage_faculty.php',
    'add_faculty.php',
    'edit_faculty.php',
    'manage_history.php',
    'add_history.php',
    'edit_history.php',
    'manage_news.php', // New
    'add_news.php',    // New
    'edit_news.php'    // New
];

if (in_array($current_page, $auth_required_pages)) {
    if (!isset($_SESSION['teacher_logged_in']) || $_SESSION['teacher_logged_in'] !== true) {
        header("Location: index.php"); // Redirect to teacher login page
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教师面板<?php if (isset($page_title)) echo " - " . htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="css/teacher_style.css">
</head>
<body>
    <header class="teacher-header">
        <div class="teacher-header-container">
            <div class="teacher-logo">
                 <img src="https://ldont.pgrm.top/img/cdfz.png" alt="昆明市盘龙区财大附中校徽" style="height: 40px; width: auto; margin-right: 10px; vertical-align: middle;">
                <h1>教师操作面板</h1>
            </div>
            <nav class="teacher-nav">
                <ul>
                    <?php if (isset($_SESSION['teacher_logged_in']) && $_SESSION['teacher_logged_in'] === true): ?>
                        <li><a href="panel.php" class="<?php echo ($current_page == 'panel.php') ? 'active' : ''; ?>">我的面板</a></li>
                        <li><a href="manage_faculty.php" class="<?php echo (in_array($current_page, ['manage_faculty.php', 'add_faculty.php', 'edit_faculty.php'])) ? 'active' : ''; ?>">管理师资力量</a></li>
                        <li><a href="manage_history.php" class="<?php echo (in_array($current_page, ['manage_history.php', 'add_history.php', 'edit_history.php'])) ? 'active' : ''; ?>">管理校史</a></li>
                        <li><a href="manage_news.php" class="<?php echo (in_array($current_page, ['manage_news.php', 'add_news.php', 'edit_news.php'])) ? 'active' : ''; ?>">新闻管理</a></li>
                        <li><a href="logout.php">登出</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="teacher-main">
        <div class="teacher-container">
        <!-- Content of specific teacher pages will go here -->
