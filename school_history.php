<!DOCTYPE html>
<?php
require_once 'config.php';
$page_title_frontend = "校史板块";
include 'templates/header.php';

$conn = get_main_db_connection();
$history_events = [];
$history_fetch_error = '';

$history_sql = "SELECT event_date, title, description, multimedia_path FROM school_history ORDER BY event_date DESC, created_at DESC";
$history_result = $conn->query($history_sql);

if ($history_result) {
    while ($row = $history_result->fetch_assoc()) {
        $history_events[] = $row;
    }
} else {
    $history_fetch_error = "无法获取校史信息：" . $conn->error;
}
// $conn->close(); // Connection closed in footer
?>

    <!-- Main content started by header.php -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">回顾[School Name]高中的悠久历史、重要里程碑及光辉传统。</p>
        </section>

        <article class="content-section-container">
            <section class="content-block">
                <h2>学校的起源与发展</h2>
                <p>自[创立年份]年创立以来，昆明市盘龙区财大附中已走过[年数]载光辉岁月。从最初的[描述早期状况，如“一座小型社区学校”]，发展成为如今[描述当前规模和声誉，如“一所备受赞誉的综合性高中”]，我们始终秉持着[提及核心教育理念，如“有教无类，追求卓越”]的办学宗旨。</p>
                <p>我们的校史是几代师生共同奋斗的见证，充满了重要的里程碑事件，这些事件塑造了我们学校的今天。其中包括[提及一两个早期重要事件，如“首次男女同校招生”，“建立第一个科学实验室”]等等。</p>
            </section>

            <section class="content-block" id="key-milestones-timeline">
                <h2>重要里程碑时间轴</h2>
                <?php if (!empty($history_fetch_error)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($history_fetch_error); ?></p>
                <?php elseif (!empty($history_events)): ?>
                    <div class="history-events-container">
                        <?php foreach ($history_events as $event): ?>
                            <article class="history-card">
                                <?php
                                $rawFilePath = $event['multimedia_path']; // Use raw path for file_exists
                                if (!empty($rawFilePath) && file_exists($rawFilePath)):
                                    $filePath = htmlspecialchars($rawFilePath); // Sanitize for HTML attributes
                                    $fileExt = strtolower(pathinfo($rawFilePath, PATHINFO_EXTENSION)); // Use raw path for pathinfo
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                    // For history, might want to allow videos to be displayed or linked differently
                                    $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                                ?>
                                    <div class="history-card-image">
                                    <?php if (in_array($fileExt, $imageExtensions)): ?>
                                        <img src="<?php echo $filePath; ?>" alt="<?php echo htmlspecialchars($event['title']); ?> 照片">
                                    <?php elseif (in_array($fileExt, $videoExtensions)): ?>
                                        <video width="100%" controls style="max-width:320px; border-radius: var(--border-radius);">
                                            <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExt=='mov'?'quicktime':$fileExt; ?>">
                                            您的浏览器不支持视频标签。
                                        </video>
                                    <?php else: ?>
                                        <a href="<?php echo $filePath; ?>" target="_blank">查看相关文件</a>
                                    <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="history-card-content">
                                    <h3 class="history-card-date"><?php echo htmlspecialchars($event['event_date']); ?></h3>
                                    <h4 class="history-card-title"><?php echo htmlspecialchars($event['title']); ?></h4>
                                    <p class="history-card-description"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>暂无校史事件记录。</p>
                <?php endif; ?>
            </section>

            <section class="content-block text-center" id="historical-archive-link">
                 <h2>探索更多历史</h2>
                 <p>我们正在积极整理和数字化学校的宝贵历史档案，包括老照片、校友回忆录和重要文献。敬请期待未来更丰富的在线校史资源。</p>
                 <!-- <a class="cta-button secondary-cta">访问校史数字档案 (建设中)</a> -->
            </section>
        </article>
    <!-- Main content ends in footer.php -->

<?php
include 'templates/footer.php';
?>
