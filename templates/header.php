<?php
// session_start(); // Optional: if frontend needs session features later
$current_page_frontend = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title_frontend) ? htmlspecialchars($page_title_frontend) . ' - ' : ''; ?>昆明市盘龙区财大附中</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Assuming CSS is in root/css/ -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="app-bar">
            <div class="logo-container">
                <img src="https://ldont.pgrm.top/img/cdfz.png" alt="昆明市盘龙区财大附中校徽" id="school-logo">
                <span class="school-name">昆明市盘龙区财大附中</span>
            </div>
            <div class="nav-links">
                <a href="index.php" class="<?php echo ($current_page_frontend == 'index.php') ? 'active' : ''; ?>">首页</a>
                <div class="nav-item dropdown">
                    <a href="about.php" class="dropdown-toggle <?php echo ($current_page_frontend == 'about.php') ? 'active' : ''; ?>" aria-haspopup="true" aria-expanded="false">关于我们</a>
                    <div class="dropdown-menu">
                        <a href="about.php#mission-vision">使命与愿景</a>
                        <a href="about.php#history">学校历史</a>
                        <a href="about.php#leadership-staff">领导团队</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="campus_services_hub.php" class="dropdown-toggle <?php echo ($current_page_frontend == 'campus_services_hub.php') ? 'active' : ''; ?>" aria-haspopup="true" aria-expanded="false">校园服务一站通</a>
                    <div class="dropdown-menu">
                        <a href="campus_services_hub.php#forum-service">论坛服务</a>
                        <a href="campus_services_hub.php#exam-dashboard-service">考试看板服务</a>
                        <a href="campus_services_hub.php#tts-service">TTS语音服务</a>
                    </div>
                </div>
                <a href="news.php" class="<?php echo ($current_page_frontend == 'news.php' || $current_page_frontend == 'news_article.php') ? 'active' : ''; ?>">校园新闻</a>
                <a href="admissions.php" class="<?php echo ($current_page_frontend == 'admissions.php') ? 'active' : ''; ?>">招生信息</a>
                <a href="faculty.php" class="<?php echo ($current_page_frontend == 'faculty.php') ? 'active' : ''; ?>">师资力量</a>
                <a href="school_history.php" class="<?php echo ($current_page_frontend == 'school_history.php') ? 'active' : ''; ?>">校史板块</a>
                <a href="campus_tour.php" class="<?php echo ($current_page_frontend == 'campus_tour.php') ? 'active' : ''; ?>">云游校园</a>
                <a href="contact.php" class="<?php echo ($current_page_frontend == 'contact.php') ? 'active' : ''; ?>">联系我们</a>
            </div>
            <div class="search-container">
                <button id="search-icon" aria-label="Search">🔍</button>
                <!-- Actual search input field could be dynamically shown here or on a separate search page -->
            </div>
            <button class="mobile-menu-button" aria-label="Open Menu">☰</button>
        </nav>
    </header>
    <main>
        <!-- Main content of each page will go here -->
