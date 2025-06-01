<?php
$page_title = "管理校史";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';

$conn = get_teacher_db_connection();
// Connection error and charset are handled by get_teacher_db_connection()

// Handle messages from add/edit operations
$action_message = '';
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'added') {
        $action_message = "<p class='success-message'>成功添加新校史事件。</p>";
    } elseif ($_GET['action'] == 'updated') {
        $action_message = "<p class='success-message'>校史事件更新成功。</p>";
    } elseif ($_GET['action'] == 'error') {
        $action_message = "<p class='error-message'>操作失败，请重试。</p>";
    }
     if (isset($_GET['msg'])) {
        $action_message .= "<p class='error-message'>" . htmlspecialchars($_GET['msg']) . "</p>";
    }
}

// Fetch history events
$history_result = $conn->query("SELECT id, event_date, title, description, multimedia_path FROM school_history ORDER BY event_date DESC, created_at DESC");

?>

<h2><?php echo $page_title; ?></h2>

<?php echo $action_message; ?>

<div class="teacher-section-header">
    <a href="add_history.php" class="teacher-button">添加新事件</a>
</div>

<div class="teacher-section">
    <h3>现有校史事件列表</h3>
    <?php if ($history_result && $history_result->num_rows > 0): ?>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>事件日期</th>
                    <th>标题</th>
                    <th>描述 (片段)</th>
                    <th>多媒体文件</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['description'], 0, 70)) . (strlen($row['description']) > 70 ? '...' : ''); ?></td>
                        <td>
                            <?php if (!empty($row['multimedia_path'])): ?>
                                <?php
                                // Path is relative to project root (e.g., ufiles/history/file.jpg)
                                // Link should be ../ufiles/history/file.jpg from teacher/
                                $filePath = '../' . $row['multimedia_path'];
                                $fileExt = strtolower(pathinfo($row['multimedia_path'], PATHINFO_EXTENSION));
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                                ?>
                                <?php if (file_exists($filePath)): ?>
                                    <?php if (in_array($fileExt, $imageExtensions)): ?>
                                        <img src="<?php echo htmlspecialchars($filePath); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 60px; height: auto;">
                                    <?php elseif (in_array($fileExt, $videoExtensions)): ?>
                                        <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">观看视频</a>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">查看文件</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    路径无效
                                <?php endif; ?>
                            <?php else: ?>
                                无
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_history.php?id=<?php echo $row['id']; ?>" class="teacher-button-small edit">编辑</a>
                            <!-- Delete functionality typically admin-only -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>目前没有校史事件记录。请添加新事件。</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'templates/footer.php';
?>
