<?php
$page_title = "管理师资力量";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';

$conn = get_teacher_db_connection();
// Connection error and charset are handled by get_teacher_db_connection()

// Handle messages from add/edit operations
$action_message = '';
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'added') {
        $action_message = "<p class='success-message'>成功添加新教职工成员。</p>";
    } elseif ($_GET['action'] == 'updated') {
        $action_message = "<p class='success-message'>教职工信息更新成功。</p>";
    } elseif ($_GET['action'] == 'error') {
        $action_message = "<p class='error-message'>操作失败，请重试。</p>";
    }
     if (isset($_GET['msg'])) {
        $action_message .= "<p class='error-message'>" . htmlspecialchars($_GET['msg']) . "</p>";
    }
}

// Fetch faculty members
$faculty_result = $conn->query("SELECT id, name, title, bio, image_path FROM faculty ORDER BY name ASC");

?>

<h2><?php echo $page_title; ?></h2>

<?php echo $action_message; ?>

<div class="teacher-section-header">
    <a href="add_faculty.php" class="teacher-button">添加新成员</a>
</div>

<div class="teacher-section">
    <h3>现有教职工列表</h3>
    <?php if ($faculty_result && $faculty_result->num_rows > 0): ?>
        <table class="teacher-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>职称</th>
                    <th>简介 (片段)</th>
                    <th>图片</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $faculty_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['bio'], 0, 50)) . (strlen($row['bio']) > 50 ? '...' : ''); ?></td>
                        <td>
                            <?php
                            // Note: image_path is stored relative to project root (e.g., ufiles/faculty/image.jpg)
                            // So, when displaying from teacher/manage_faculty.php, the path needs to be ../ufiles/faculty/image.jpg
                            if (!empty($row['image_path']) && file_exists('../' . $row['image_path'])):
                            ?>
                                <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 50px; height: auto;">
                            <?php else: ?>
                                无图片
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_faculty.php?id=<?php echo $row['id']; ?>" class="teacher-button-small edit">编辑</a>
                            <!-- Delete functionality is typically admin-only, so not included for teachers -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>目前没有教职工信息。请添加新成员。</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'templates/footer.php';
?>
