<?php
$page_title = "添加新文章";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';     // Database connection

$conn = get_db_connection();

$title = '';
$content = '';
$author_name = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : '站点管理员'; // Pre-fill with admin username or default
$error_message = '';

// Define upload directory relative to the project root (where ufiles is)
define('UPLOAD_DIR_PROJECT_ROOT_NEWS', 'ufiles/news/');
// Define upload directory relative to THIS SCRIPT's location (admin/) for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT_NEWS', '../' . UPLOAD_DIR_PROJECT_ROOT_NEWS);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']); // Content can be HTML, consider using a WYSIWYG editor in a real app
    $author_name = trim($_POST['author_name']);
    $multimedia_path_for_db = null;
    $token_used = 'ADMIN_ENTRY'; // Special value for admin entries

    // Basic validation
    if (empty($title)) {
        $error_message = "标题是必填项。";
    }
    if (empty($author_name)) { // Author name defaults but can be emptied by user
        $author_name = '站点管理员';
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
                $stmt_insert->bind_param("sssss", $title, $content, $author_name, $token_used, $multimedia_path_for_db);
                if ($stmt_insert->execute()) {
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
        <label for="author_name">作者名称:</label>
        <input type="text" id="author_name" name="author_name" value="<?php echo htmlspecialchars($author_name); ?>">
    </div>
    <div class="form-group">
        <label for="multimedia">相关多媒体 (可选, 图片或视频):</label>
        <input type="file" id="multimedia" name="multimedia" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm">
    </div>
    <div class="form-group">
        <input type="submit" value="发布文章" class="admin-button">
        <a href="manage_news.php" class="admin-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
