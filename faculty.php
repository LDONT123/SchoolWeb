<!DOCTYPE html>
<?php
require_once 'config.php';
$page_title_frontend = "师资力量";
include 'templates/header.php';

$conn = get_main_db_connection();
$faculty_list = [];
$faculty_fetch_error = '';

$faculty_sql = "SELECT name, title, bio, image_path FROM faculty ORDER BY name ASC"; // Or by a specific order column if added
$faculty_result = $conn->query($faculty_sql);

if ($faculty_result) {
    while ($row = $faculty_result->fetch_assoc()) {
        $faculty_list[] = $row;
    }
} else {
    $faculty_fetch_error = "无法获取师资信息：" . $conn->error;
}
// $conn->close(); // Connection closed in footer
?>

    <!-- Main content started by header.php -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">了解我们敬业且经验丰富的教职员工团队，他们是学生成长的引路人。</p>
        </section>

        <article class="content-section-container">
            <section class="content-block">
                <h2>我们的教学理念</h2>
                <p>在昆明市盘龙区财大附中，我们坚信优秀的教师是优质教育的核心。我们的教师团队不仅具备深厚的学科知识和丰富的教学经验，更对教育事业充满热情，关爱每一位学生的成长。我们致力于创造一个启发思考、鼓励探索、支持个性化发展的学习环境。</p>
            </section>

            <section class="faculty-grid-container card-based-section">
                <h2>教师团队介绍</h2>
                <?php if (!empty($faculty_fetch_error)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($faculty_fetch_error); ?></p>
                <?php elseif (!empty($faculty_list)): ?>
                    <div class="faculty-grid">
                        <?php foreach ($faculty_list as $faculty_member): ?>
                            <article class="faculty-card">
                                <div class="faculty-card-image">
                                    <?php if (!empty($faculty_member['image_path']) && file_exists($faculty_member['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($faculty_member['image_path']); ?>" alt="<?php echo htmlspecialchars($faculty_member['name']); ?> 照片">
                                    <?php else: ?>
                                        <img src="images/placeholder_teacher_default.png" alt="默认教师照片占位图"> <!-- Default placeholder -->
                                    <?php endif; ?>
                                </div>
                                <div class="faculty-card-content">
                                    <h4 class="faculty-card-name"><?php echo htmlspecialchars($faculty_member['name']); ?></h4>
                                    <p class="faculty-card-title"><?php echo htmlspecialchars($faculty_member['title']); ?></p>
                                    <p class="faculty-card-bio"><?php echo nl2br(htmlspecialchars($faculty_member['bio'])); ?></p>
                                    <!-- <a class="card-link">了解更多</a> Placeholder if individual pages were planned -->
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>暂无师资信息。</p>
                <?php endif; ?>
            </section>
        </article>
    <!-- Main content ends in footer.php -->

<?php
include 'templates/footer.php';
?>
