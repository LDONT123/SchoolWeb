<?php
$page_title = "添加新校史事件";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';

$conn = get_teacher_db_connection();
// Connection error and charset are handled by get_teacher_db_connection()

$event_date = '';
$title = '';
$description = '';
$error_message = '';
// Note: success_message is not typically used here as we redirect

// Define upload directory relative to the project root (where ufiles is)
define('UPLOAD_DIR_PROJECT_ROOT_HISTORY', 'ufiles/history/');
// Define upload directory relative to THIS SCRIPT's location (teacher/) for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT_HISTORY', '../' . UPLOAD_DIR_PROJECT_ROOT_HISTORY);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_date = trim($_POST['event_date']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $multimedia_path_for_db = null;

    // Basic validation
    if (empty($title)) {
        $error_message = "标题是必填项。";
    } else {
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
                'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'application/octet-stream' // Sometimes mov files are octet-stream
            ];

            // A more robust check for mov files specifically if mime_content_type is octet-stream
            if ($file_ext == 'mov' && $file_type == 'application/octet-stream') {
                 // Allow it, assuming it's a mov file
            } elseif (!in_array($file_ext, $allowed_extensions) || !in_array($file_type, $allowed_mime_types)) {
                 $error_message = "不允许的文件类型。允许的图片格式：JPG, PNG, GIF。允许的视频格式：MP4, MOV, AVI, WEBM (MIME: " . $file_type .")";
            }


            if (empty($error_message)) { // Proceed if no mime/ext error
                $max_size = (in_array($file_ext, ['mp4', 'mov', 'avi', 'webm'])) ? 50 * 1024 * 1024 : 2 * 1024 * 1024;

                if ($file_size <= $max_size) {
                    $unique_file_name = time() . '_' . uniqid() . '.' . $file_ext;
                    $target_file_path_from_script = UPLOAD_DIR_FROM_SCRIPT_HISTORY . $unique_file_name;

                    if (!is_dir(UPLOAD_DIR_FROM_SCRIPT_HISTORY)) {
                        if (!mkdir(UPLOAD_DIR_FROM_SCRIPT_HISTORY, 0777, true)) {
                             $error_message = "错误：无法创建上传目录。请检查服务器权限。";
                        }
                    }

                    if (empty($error_message) && is_writable(UPLOAD_DIR_FROM_SCRIPT_HISTORY)) {
                        if (move_uploaded_file($file_tmp_name, $target_file_path_from_script)) {
                            $multimedia_path_for_db = UPLOAD_DIR_PROJECT_ROOT_HISTORY . $unique_file_name;
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
            $stmt_insert = $conn->prepare("INSERT INTO school_history (event_date, title, description, multimedia_path) VALUES (?, ?, ?, ?)");
            if ($stmt_insert) {
                $stmt_insert->bind_param("ssss", $event_date, $title, $description, $multimedia_path_for_db);
                if ($stmt_insert->execute()) {
                    // Log Action
                    $action_taken = "创建校史事件: " . $title . ( $multimedia_path_for_db ? " (附多媒体文件)" : " (无多媒体文件)");
                    $token_value = $_SESSION['teacher_token']; // From teacher login
                    $teacher_name_session = $_SESSION['teacher_name']; // From teacher login

                    $stmt_log = $conn->prepare("INSERT INTO token_logs (token_value, teacher_name_entered, action_taken) VALUES (?, ?, ?)");
                    if($stmt_log){
                        $stmt_log->bind_param("sss", $token_value, $teacher_name_session, $action_taken);
                        $stmt_log->execute();
                        $stmt_log->close();
                    } // Error logging for token_logs can be added if critical

                    header("Location: manage_history.php?action=added");
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

<h2><?php echo $page_title; ?></h2>

<?php if ($error_message): ?>
    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="event_date">事件日期 (例如：1995年春, 2003年10月):</label>
        <input type="text" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event_date); ?>">
    </div>
    <div class="form-group">
        <label for="title">标题 (必填):</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">描述:</label>
        <textarea id="description" name="description" rows="8"><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    <div class="form-group">
        <label for="multimedia">多媒体文件 (可选, 图片或视频, 最大2MB图片/50MB视频):</label>
        <input type="file" id="multimedia" name="multimedia" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm">
    </div>
    <div class="form-group">
        <input type="submit" value="添加事件" class="teacher-button">
        <a href="manage_history.php" class="teacher-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
