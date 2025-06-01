<?php
require_once 'config.php'; // DB credentials and NEWS_PER_PAGE constant
$page_title_frontend = "校园新闻"; // For header.php
include 'templates/header.php';

$conn = get_main_db_connection();

// Pagination variables
$current_page_get = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page_get < 1) {
    $current_page_get = 1;
}
$offset = ($current_page_get - 1) * NEWS_PER_PAGE;

// Get total number of news articles
$total_news_query = $conn->query("SELECT COUNT(*) AS total FROM campus_news");
$total_news_count = 0;
if ($total_news_query && $total_news_query->num_rows > 0) {
    $total_news_count = $total_news_query->fetch_assoc()['total'];
}
$total_pages = ceil($total_news_count / NEWS_PER_PAGE);

// Fetch articles for the current page
$news_stmt = $conn->prepare("SELECT id, title, content, author_name, publication_date, multimedia_path FROM campus_news ORDER BY publication_date DESC LIMIT ? OFFSET ?");
$news_stmt->bind_param("ii", NEWS_PER_PAGE, $offset);
$news_stmt->execute();
$news_result = $news_stmt->get_result();
?>

<section class="page-title-section">
    <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
    <p class="page-subtitle">了解昆明市盘龙区财大附中的最新动态与重要通知。</p>
</section>

<div class="content-section-container news-listing-container">
    <?php if ($news_result && $news_result->num_rows > 0): ?>
        <?php while ($article = $news_result->fetch_assoc()): ?>
            <article class="news-item-summary card">
                <?php if (!empty($article['multimedia_path'])):
                    $filePath = htmlspecialchars($article['multimedia_path']); // Already relative to project root
                    $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    // For listing, only show images as thumbnails
                    if (in_array($fileExt, $imageExtensions) && file_exists($filePath)):
                ?>
                    <div class="news-summary-image">
                        <a href="news_article.php?id=<?php echo $article['id']; ?>">
                            <img src="<?php echo $filePath; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </a>
                    </div>
                <?php
                    endif;
                endif;
                ?>
                <div class="news-summary-content">
                    <h2><a href="news_article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h2>
                    <p class="news-meta">
                        发布于：<?php echo date("Y年m月d日", strtotime($article['publication_date'])); ?>
                        <?php if (!empty($article['author_name'])): ?>
                            &nbsp;|&nbsp; 作者：<?php echo htmlspecialchars($article['author_name']); ?>
                        <?php endif; ?>
                    </p>
                    <p class="news-excerpt">
                        <?php
                        $content_plaintext = strip_tags($article['content']);
                        echo htmlspecialchars(mb_substr($content_plaintext, 0, 150, 'UTF-8')) . (mb_strlen($content_plaintext, 'UTF-8') > 150 ? '...' : '');
                        ?>
                    </p>
                    <a href="news_article.php?id=<?php echo $article['id']; ?>" class="card-link">阅读全文 &raquo;</a>
                </div>
            </article>
        <?php endwhile; ?>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="pagination">
                <ul>
                    <?php if ($current_page_get > 1): ?>
                        <li><a href="news.php?page=<?php echo $current_page_get - 1; ?>">&laquo; 上一页</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $current_page_get): ?>
                            <li class="active"><span><?php echo $i; ?></span></li>
                        <?php else: ?>
                            <li><a href="news.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($current_page_get < $total_pages): ?>
                        <li><a href="news.php?page=<?php echo $current_page_get + 1; ?>">下一页 &raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <p>目前没有新闻文章。</p>
    <?php endif; ?>
</div>

<?php
$news_stmt->close();
$conn->close();
include 'templates/footer.php';
?>
