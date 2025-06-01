<?php
$page_title = "新闻管理";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';     // Database connection

$conn = get_teacher_db_connection();

// Handle messages from add/edit operations
$action_message = '';
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'added') {
        $action_message = "<p class='success-message'>成功发布新文章。</p>";
    } elseif ($_GET['action'] == 'updated') {
        $action_message = "<p class='success-message'>新闻文章更新成功。</p>";
    } elseif ($_GET['action'] == 'error') {
        $action_message = "<p class='error-message'>操作失败，请重试。</p>";
    }
     if (isset($_GET['msg'])) {
        $action_message .= "<p class='error-message'>" . htmlspecialchars($_GET['msg']) . "</p>";
    }
}

// Fetch campus news articles
// Teachers should ideally see all news, or news they authored if that was a requirement.
// For now, fetching all news.
$news_result = $conn->query("SELECT id, title, author_name, publication_date, updated_at FROM campus_news ORDER BY publication_date DESC");

?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<?php echo $action_message; ?>

<div class="teacher-section-header">
    <a href="add_news.php" class="teacher-button">添加新文章</a>
</div>

<div class="teacher-section">
    <h3>现有新闻文章列表</h3>
    <?php if ($news_result && $news_result->num_rows > 0): ?>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>作者</th>
                    <th>发布日期</th>
                    <th>最后更新</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $news_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author_name'] ? $row['author_name'] : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['publication_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                        <td>
                            <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="teacher-button-small edit">编辑</a>
                            <!-- No Delete for teachers -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>目前没有新闻文章。请添加新文章。</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'templates/footer.php';
?>
