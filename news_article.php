<?php
require_once 'config.php'; // DB credentials
include_once 'templates/header.php'; // Start HTML output, session etc.

$conn = get_main_db_connection();

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = null;

if ($article_id > 0) {
    $stmt = $conn->prepare("SELECT title, content, author_name, publication_date, multimedia_path FROM campus_news WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $article = $result->fetch_assoc();
        $page_title_frontend = $article['title']; // Set page title for header
    }
    $stmt->close();
}

// Re-include header if page title has changed
if (isset($page_title_frontend)) {
    // This is a bit of a hack due to header already being included.
    // Ideally, $page_title_frontend would be set BEFORE including header.php the first time.
    // For this structure, we might just update the <title> tag via JS or accept this limitation.
    // Or, output header content directly here if $article found.
    // For now, we'll rely on the initial header include. The title will be generic if article not found early.
}


if ($article):
?>

<div class="content-section-container article-container">
    <article class="news-full-article content-block">
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <p class="article-meta">
            发布于：<?php echo date("Y年m月d日 H:i", strtotime($article['publication_date'])); ?>
            <?php if (!empty($article['author_name'])): ?>
                &nbsp;|&nbsp; 作者：<?php echo htmlspecialchars($article['author_name']); ?>
            <?php endif; ?>
        </p>

        <?php if (!empty($article['multimedia_path'])):
            $filePath = htmlspecialchars($article['multimedia_path']); // Path relative to project root
            $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];

            if (file_exists($filePath)):
        ?>
            <div class="article-multimedia">
                <?php if (in_array($fileExt, $imageExtensions)): ?>
                    <img src="<?php echo $filePath; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="max-width: 100%; height: auto; border-radius: var(--border-radius); margin-bottom: 20px;">
                <?php elseif (in_array($fileExt, $videoExtensions)): ?>
                    <video width="100%" style="max-width: 640px; margin-bottom:20px; border-radius: var(--border-radius);" controls>
                      <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExt=='mov'?'quicktime':$fileExt; ?>">
                      您的浏览器不支持HTML5视频。 <a href="<?php echo $filePath; ?>">直接下载视频</a>
                    </video>
                <?php else: ?>
                    <p><a href="<?php echo $filePath; ?>" target="_blank">查看相关文件</a></p>
                <?php endif; ?>
            </div>
        <?php
            endif;
        endif;
        ?>

        <div class="article-content">
            <?php
            // Assuming content might be plain text with newlines, or basic HTML.
            // If it's stored as HTML from a WYSIWYG, direct echo is fine (after sanitization on input).
            // If it's plain text and newlines should be preserved:
            echo nl2br(htmlspecialchars($article['content']));
            // If it's trusted HTML (e.g., saved from an admin WYSIWYG editor after sanitization):
            // echo $article['content'];
            ?>
        </div>
        <div class="back-to-news">
            <a href="news.php" class="cta-button secondary-cta">&laquo; 返回新闻列表</a>
        </div>
    </article>
</div>

<?php else: ?>
<div class="content-section-container">
    <section class="content-block text-center">
        <h2>文章未找到</h2>
        <p>抱歉，您所请求的文章不存在或已被删除。</p>
        <a href="news.php" class="cta-button primary-cta">返回新闻列表</a>
    </section>
</div>
<?php
endif;

$conn->close();
include 'templates/footer.php';
?>
