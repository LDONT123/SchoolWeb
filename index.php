<!DOCTYPE html>
<?php
require_once 'config.php'; // For DB credentials and NEWS_PER_PAGE, though NEWS_PER_PAGE not used here
$page_title_frontend = "首页"; // For header.php
include 'templates/header.php';

$conn = get_main_db_connection();
$latest_news = [];
$news_fetch_error = '';

$news_sql = "SELECT id, title, content, publication_date, multimedia_path FROM campus_news ORDER BY publication_date DESC LIMIT 3";
$news_result_index = $conn->query($news_sql);

if ($news_result_index) {
    while ($row = $news_result_index->fetch_assoc()) {
        $latest_news[] = $row;
    }
} else {
    $news_fetch_error = "无法获取最新新闻：" . $conn->error; // For debugging
}
// $conn->close(); // Connection will be closed in footer.php or after all DB ops if any more needed on this page
?>
    <!-- Main content was previously under <main>, header.php now starts <main> -->
        <!-- Hero Section -->
        <section class="hero-section">
            <!-- Placeholder: Add a relevant, high-quality background image via CSS -->
            <div class="hero-content">
                <h1>创新。创造。成功。</h1>
                <p>欢迎来到昆明市盘龙区财大附中！在这里，学生们被赋予探索激情、实现全部潜能的力量。加入我们的学习者和领导者社区吧！</p>
                <a href="campus_tour.html" class="cta-button primary-cta">🚀 云游校园</a>
                <a href="admissions.html" class="cta-button secondary-cta">了解招生信息</a>
            </div>
        </section>

        <!-- News & Announcements Section -->
        <section class="card-based-section" id="news-announcements">
            <h2>最新消息与公告</h2>
            <div class="card-container">
                <article class="card">
                    <img src="images/placeholder_news1.jpg" alt="学生们在科学项目中合作">
                    <h3>科学竞赛获奖名单揭晓！</h3>
                    <p>祝贺在年度昆明市盘龙区财大附中科学竞赛中展示了卓越项目的才华横溢的同学们！点击查看获奖者及其创新成果。</p>
                    <time datetime="2024-05-28">2024年5月28日</time>
                    <a class="card-link">阅读更多</a>
                </article>
                <article class="card">
                    <img src="images/placeholder_news2.jpg" alt="学校辩论队成员合影">
                    <h3>辩论队在州锦标赛中表现出色！</h3>
                    <p>我们才华横溢的辩论队在州锦标赛中荣获亚军。他们的辛勤付出和奉献精神得到了回报！</p>
                    <time datetime="2024-05-25">2024年5月25日</time>
                    <a class="card-link">阅读更多</a>
                </article>
                <article class="card">
                    <img src="images/placeholder_news3.jpg" alt="学生参与社区服务活动">
                    <h3>年度社区服务日圆满成功！</h3>
                    <p>感谢所有参与年度社区服务日的师生，你们为本地社区做出了积极贡献。</p>
                    <time datetime="2024-05-22">2024年5月22日</time>
                    <a class="card-link">阅读更多</a>
                </article>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section class="card-based-section" id="upcoming-events">
            <h2>近期活动</h2>
            <div class="card-container">
                <article class="card">
                    <div class="event-date">
                        <span class="month">六月</span>
                        <span class="day">15</span>
                    </div>
                    <div class="event-details">
                        <h3>家长教师会议</h3>
                        <p>家长与教师讨论学生学习进展的机会。报名即将开放。</p>
                        <a class="card-link">查看详情与安排</a>
                    </div>
                </article>
                <article class="card">
                    <div class="event-date">
                        <span class="month">六月</span>
                        <span class="day">20</span>
                    </div>
                    <div class="event-details">
                        <h3>春季音乐会</h3>
                        <p>加入我们，欣赏才华横溢的学生音乐家们带来的精彩纷呈的晚间演出。免费入场。</p>
                        <a class="card-link">了解更多</a>
                    </div>
                </article>
                <article class="card">
                    <div class="event-date">
                        <span class="month">七月</span>
                        <span class="day">05</span>
                    </div>
                    <div class="event-details">
                        <h3>夏季编程训练营开始</h3>
                        <p>为您的孩子报名参加我们引人入胜的夏季编程训练营吧！名额有限！</p>
                        <a class="card-link">立即报名</a>
                    </div>
                </article>
            </div>
            <div class="text-center section-cta">
                <a class="cta-button secondary-cta">查看完整活动日历</a>
            </div>
        </section>

        <!-- Homepage News Summary Section -->
        <section class="card-based-section" id="homepage-news-summary">
            <h2>校园快讯</h2>
            <?php if (!empty($news_fetch_error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($news_fetch_error); ?></p>
            <?php elseif (!empty($latest_news)): ?>
                <div class="news-summary-grid">
                    <?php foreach ($latest_news as $article): ?>
                        <article class="news-summary-item card">
                            <?php if (!empty($article['multimedia_path'])):
                                $filePath = htmlspecialchars($article['multimedia_path']);
                                $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                if (in_array($fileExt, $imageExtensions) && file_exists($filePath)):
                            ?>
                            <div class="news-summary-item-image">
                                <a href="news_article.php?id=<?php echo $article['id']; ?>">
                                    <img src="<?php echo $filePath; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                </a>
                            </div>
                            <?php
                                endif;
                            endif;
                            ?>
                            <div class="news-summary-item-content">
                                <h3><a href="news_article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
                                <p class="news-meta-summary"><?php echo date("Y年m月d日", strtotime($article['publication_date'])); ?></p>
                                <p class="news-excerpt-summary">
                                    <?php
                                    $content_plaintext = strip_tags($article['content']);
                                    echo htmlspecialchars(mb_substr($content_plaintext, 0, 60, 'UTF-8')) . (mb_strlen($content_plaintext, 'UTF-8') > 60 ? '...' : '');
                                    ?>
                                </p>
                                <a href="news_article.php?id=<?php echo $article['id']; ?>" class="card-link">阅读更多 &raquo;</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="text-center section-cta">
                    <a href="news.php" class="cta-button secondary-cta">查看更多新闻</a>
                </div>
            <?php else: ?>
                <p>暂无最新新闻。</p>
            <?php endif; ?>
        </section>

        <!-- Explore Our Academics Section -->
        <section class="card-based-section" id="explore-academics">
            <h2>卓越之路</h2>
            <div class="card-container">
                <article class="card">
                    <div class="card-icon"><span>🔬</span></div>
                    <h3>前沿STEM课程</h3>
                    <p>参与旨在培养创新和批判性思维的前沿科学、技术、工程和数学（STEM）项目。</p>
                    <a href="campus_services_hub.html#stem" class="card-link">探索STEM</a>
                </article>
                <article class="card">
                    <div class="card-icon"><span>🎭</span></div>
                    <h3>活力人文艺术</h3>
                    <p>通过我们多样化的视觉艺术、表演艺术和人文学科课程，发现您的创造力。</p>
                    <a href="campus_services_hub.html#arts" class="card-link">发现人文艺术</a>
                </article>
                <article class="card">
                    <div class="card-icon"><span>🏆</span></div>
                    <h3>领导力与发展</h3>
                    <p>参与培养领导技能、鼓励团队合作和促进个人成长的项目。</p>
                    <a href="campus_services_hub.html#leadership" class="card-link">与我们共成长</a>
                </article>
            </div>
        </section>

        <!-- Quick Links / Portals Section -->
        <section class="card-based-section" id="quick-links">
            <h2>快速访问</h2>
            <div class="quick-links-container">
                <a class="quick-link-card"><span>👤</span> 家长门户</a>
                <a class="quick-link-card"><span>🧑‍🎓</span> 学生门户</a>
                <a class="quick-link-card"><span>📚</span> 图书馆目录</a>
                <a class="quick-link-card"><span>🍽️</span> 午餐菜单</a>
                <a class="quick-link-card"><span>📅</span> 学校日历</a>
                <a class="quick-link-card"><span>🏆</span> 体育赛事安排</a>
            </div>
        </section>
    </main>

<?php
include 'templates/footer.php';
?>
