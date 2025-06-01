<?php
$page_title = "添加新文章";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';     // Database connection

$conn = get_teacher_db_connection();

$title = '';
$content = '';
// Author name will be pre-filled from session, not from POST initially for add
$author_name = isset($_SESSION['teacher_name']) ? htmlspecialchars($_SESSION['teacher_name']) : '教师';
$error_message = '';

// Define upload directory relative to the project root (where ufiles is)
define('UPLOAD_DIR_PROJECT_ROOT_NEWS', 'ufiles/news/');
// Define upload directory relative to THIS SCRIPT's location (teacher/) for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT_NEWS', '../' . UPLOAD_DIR_PROJECT_ROOT_NEWS);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']); // Content can be HTML
    // Author name is taken from session for teacher-added news
    $author_name_from_session = isset($_SESSION['teacher_name']) ? $_SESSION['teacher_name'] : '未知教师';
    $multimedia_path_for_db = null;
    $token_used = isset($_SESSION['teacher_token']) ? $_SESSION['teacher_token'] : null;

    // Basic validation
    if (empty($title)) {
        $error_message = "标题是必填项。";
    }
    if ($token_used === null) {
        $error_message = "错误：无法获取教师Token，请重新登录。";
    }


    if (empty($error_message)) {
        // File upload handling
        if (isset($_FILES['multimedia']) && $_FILES['multimedia']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['multimedia']['tmp_name'];
            $file_name = $_FILES['multimedia']['name'];
            $file_size = $_FILES['multimedia']['size'];
            $file_type = mime_content_type($file_tmp_name);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'webm'];
            $allowed_mime_types = [
                'image/jpeg', 'image/png', 'image/gif',
                'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'application/octet-stream'
            ];

            if ($file_ext == 'mov' && $file_type == 'application/octet-stream') { /* Allow .mov */ }
            elseif (!in_array($file_ext, $allowed_extensions) || !in_array($file_type, $allowed_mime_types)) {
                 $error_message = "不允许的文件类型 (MIME: " . htmlspecialchars($file_type) .")";
            }

            if (empty($error_message)) {
                $max_size = (in_array($file_ext, ['mp4', 'mov', 'avi', 'webm'])) ? 50 * 1024 * 1024 : 2 * 1024 * 1024;

                if ($file_size <= $max_size) {
                    $unique_file_name = time() . '_' . uniqid() . '.' . $file_ext;
                    $target_file_path_from_script = UPLOAD_DIR_FROM_SCRIPT_NEWS . $unique_file_name;

                    if (!is_dir(UPLOAD_DIR_FROM_SCRIPT_NEWS)) {
                        if (!mkdir(UPLOAD_DIR_FROM_SCRIPT_NEWS, 0777, true)) {
                             $error_message = "错误：无法创建上传目录。请检查服务器权限。";
                        }
                    }

                    if (empty($error_message) && is_writable(UPLOAD_DIR_FROM_SCRIPT_NEWS)) {
                        if (move_uploaded_file($file_tmp_name, $target_file_path_from_script)) {
                            $multimedia_path_for_db = UPLOAD_DIR_PROJECT_ROOT_NEWS . $unique_file_name;
                        } else {
                            $error_message = "上传文件失败。";
                        }
                    } elseif(empty($error_message)) {
                         $error_message = "错误：上传目录不可写。请检查服务器权限。";
                    }
                } else {
                    $error_message = "文件大小过大。图片最大2MB，视频最大50MB。";
                }
            }
        } elseif (isset($_FILES['multimedia']) && $_FILES['multimedia']['error'] != UPLOAD_ERR_NO_FILE) {
            $error_message = "文件上传时发生错误，错误代码：" . $_FILES['multimedia']['error'];
        }

        if (empty($error_message)) {
            // publication_date is set by DB DEFAULT CURRENT_TIMESTAMP
            $stmt_insert = $conn->prepare("INSERT INTO campus_news (title, content, author_name, token_used, multimedia_path) VALUES (?, ?, ?, ?, ?)");
            if ($stmt_insert) {
                $stmt_insert->bind_param("sssss", $title, $content, $author_name_from_session, $token_used, $multimedia_path_for_db);
                if ($stmt_insert->execute()) {
                    // Log Action
                    $action_taken = "创建新闻文章: " . $title;
                    $stmt_log = $conn->prepare("INSERT INTO token_logs (token_value, teacher_name_entered, action_taken) VALUES (?, ?, ?)");
                    if($stmt_log){
                        $stmt_log->bind_param("sss", $token_used, $author_name_from_session, $action_taken);
                        $stmt_log->execute();
                        $stmt_log->close();
                    }

                    header("Location: manage_news.php?action=added");
                    exit;
                } else {
                    $error_message = "数据库插入失败: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            } else {
                 $error_message = "数据库准备语句失败: " . $conn->error;
            }
        }
    }
}
$conn->close();
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<?php if ($error_message): ?>
    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">标题 (必填):</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="content">内容:</label>
        <textarea id="content" name="content" rows="10"><?php echo htmlspecialchars($content); ?></textarea>
        <!-- Consider a WYSIWYG editor here for richer content -->
    </div>
    <div class="form-group">
        <label for="author_name_display">作者名称 (自动记录为当前登录教师):</label>
        <input type="text" id="author_name_display" name="author_name_display" value="<?php echo $author_name; ?>" readonly disabled>
        <!-- Hidden field for actual author name to be submittable if needed, though we use session -->
        <input type="hidden" name="author_name" value="<?php echo $author_name; ?>">
    </div>
    <div class="form-group">
        <label for="multimedia">相关多媒体 (可选, 图片或视频):</label>
        <input type="file" id="multimedia" name="multimedia" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm">
    </div>
    <div class="form-group">
        <input type="submit" value="发布文章" class="teacher-button">
        <a href="manage_news.php" class="teacher-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
