<?php
$page_title = "编辑校史事件";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';

$conn = get_teacher_db_connection();
// Connection error and charset are handled by get_teacher_db_connection()

$history_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event_date = '';
$title = '';
$description = '';
$current_multimedia_path = ''; // Relative to project root
$error_message = '';

// Define upload directory relative to the project root (where ufiles is)
define('UPLOAD_DIR_PROJECT_ROOT_HISTORY', 'ufiles/history/');
// Define upload directory relative to THIS SCRIPT's location (teacher/) for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT_HISTORY', '../' . UPLOAD_DIR_PROJECT_ROOT_HISTORY);


if ($history_id <= 0) {
    header("Location: manage_history.php?action=error&msg=" . urlencode("无效的校史事件ID。"));
    exit;
}

// Fetch existing data
$stmt_fetch = $conn->prepare("SELECT event_date, title, description, multimedia_path FROM school_history WHERE id = ?");
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $history_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($result->num_rows === 1) {
        $history_event = $result->fetch_assoc();
        $event_date = $history_event['event_date'];
        $title = $history_event['title'];
        $description = $history_event['description'];
        $current_multimedia_path = $history_event['multimedia_path'];
    } else {
        header("Location: manage_history.php?action=error&msg=" . urlencode("未找到指定的校史事件。"));
        exit;
    }
    $stmt_fetch->close();
} else {
    die("<p class='error-message'>获取校史事件信息失败: " . $conn->error . "</p>");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_date_updated = trim($_POST['event_date']);
    $title_updated = trim($_POST['title']);
    $description_updated = trim($_POST['description']);
    $new_multimedia_path_for_db = $current_multimedia_path;

    if (empty($title_updated)) {
        $error_message = "标题是必填项。";
    } else {
        // File upload handling for new multimedia file
        if (isset($_FILES['new_multimedia']) && $_FILES['new_multimedia']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['new_multimedia']['tmp_name'];
            $file_name = $_FILES['new_multimedia']['name'];
            $file_size = $_FILES['new_multimedia']['size'];
            $file_type = mime_content_type($file_tmp_name);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'webm'];
            $allowed_mime_types = [
                'image/jpeg', 'image/png', 'image/gif',
                'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'application/octet-stream'
            ];

            if ($file_ext == 'mov' && $file_type == 'application/octet-stream') {
                // Allow it
            } elseif (!in_array($file_ext, $allowed_extensions) || !in_array($file_type, $allowed_mime_types)) {
                 $error_message = "不允许的文件类型。MIME: " . $file_type;
            }

            if (empty($error_message)) {
                $max_size = (in_array($file_ext, ['mp4', 'mov', 'avi', 'webm'])) ? 50 * 1024 * 1024 : 2 * 1024 * 1024;

                if ($file_size <= $max_size) {
                    $unique_file_name = time() . '_' . uniqid() . '.' . $file_ext;
                    $target_file_path_from_script = UPLOAD_DIR_FROM_SCRIPT_HISTORY . $unique_file_name;

                    if (!is_dir(UPLOAD_DIR_FROM_SCRIPT_HISTORY)) {
                        mkdir(UPLOAD_DIR_FROM_SCRIPT_HISTORY, 0777, true);
                    }

                    if (is_writable(UPLOAD_DIR_FROM_SCRIPT_HISTORY)) {
                        if (move_uploaded_file($file_tmp_name, $target_file_path_from_script)) {
                            if ($current_multimedia_path && file_exists('../' . $current_multimedia_path)) {
                                unlink('../' . $current_multimedia_path);
                            }
                            $new_multimedia_path_for_db = UPLOAD_DIR_PROJECT_ROOT_HISTORY . $unique_file_name;
                        } else {
                            $error_message = "上传新文件失败。";
                        }
                    } else {
                         $error_message = "错误：上传目录不可写。";
                    }
                } else {
                    $error_message = "新文件大小过大。图片最大2MB，视频最大50MB。";
                }
            }
        } elseif (isset($_FILES['new_multimedia']) && $_FILES['new_multimedia']['error'] != UPLOAD_ERR_NO_FILE) {
            $error_message = "新文件上传时发生错误，错误代码：" . $_FILES['new_multimedia']['error'];
        } elseif (isset($_POST['delete_current_multimedia']) && $_POST['delete_current_multimedia'] == '1') {
            if ($current_multimedia_path && file_exists('../' . $current_multimedia_path)) {
                unlink('../' . $current_multimedia_path);
            }
            $new_multimedia_path_for_db = null;
        }

        if (empty($error_message)) {
            $stmt_update = $conn->prepare("UPDATE school_history SET event_date = ?, title = ?, description = ?, multimedia_path = ? WHERE id = ?");
            if ($stmt_update) {
                $stmt_update->bind_param("ssssi", $event_date_updated, $title_updated, $description_updated, $new_multimedia_path_for_db, $history_id);
                if ($stmt_update->execute()) {
                    // Log Action
                    $action_taken = "更新校史事件 ID: " . $history_id . ", 标题: " . $title_updated;
                    $token_value = $_SESSION['teacher_token'];
                    $teacher_name_session = $_SESSION['teacher_name'];

                    $stmt_log = $conn->prepare("INSERT INTO token_logs (token_value, teacher_name_entered, action_taken) VALUES (?, ?, ?)");
                    if($stmt_log){
                        $stmt_log->bind_param("sss", $token_value, $teacher_name_session, $action_taken);
                        $stmt_log->execute();
                        $stmt_log->close();
                    }

                    header("Location: manage_history.php?action=updated");
                    exit;
                } else {
                    $error_message = "数据库更新失败: " . $stmt_update->error;
                }
                $stmt_update->close();
            } else {
                 $error_message = "数据库准备语句失败: " . $conn->error;
            }
        }
        // If error, repopulate fields with submitted data for correction
        $event_date = $event_date_updated;
        $title = $title_updated;
        $description = $description_updated;
        if ($new_multimedia_path_for_db !== $current_multimedia_path && $error_message && !(isset($_POST['delete_current_multimedia']) && $_POST['delete_current_multimedia'] == '1')) {
            // New upload failed, don't change $current_multimedia_path for display
        } else {
            $current_multimedia_path = $new_multimedia_path_for_db;
        }
    }
}
$conn->close();
?>

<h2><?php echo $page_title; ?> (ID: <?php echo $history_id; ?>)</h2>

<?php if ($error_message): ?>
    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $history_id; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="event_date">事件日期:</label>
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

    <?php if ($current_multimedia_path): ?>
    <div class="form-group">
        <label>当前多媒体文件:</label>
        <?php
        $fullPath = '../' . $current_multimedia_path;
        if (file_exists($fullPath)):
            $fileExt = strtolower(pathinfo($current_multimedia_path, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
        ?>
            <?php if (in_array($fileExt, $imageExtensions)): ?>
                <img src="<?php echo htmlspecialchars($fullPath); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="max-width: 200px; height: auto; margin-bottom: 10px; display:block;">
            <?php elseif (in_array($fileExt, $videoExtensions)): ?>
                <video width="320" height="240" controls style="margin-bottom: 10px; display:block;">
                  <source src="<?php echo htmlspecialchars($fullPath); ?>" type="video/<?php echo $fileExt=='mov'?'quicktime':$fileExt; ?>">
                  您的浏览器不支持视频标签。
                </video>
            <?php else: ?>
                 <a href="<?php echo htmlspecialchars($fullPath); ?>" target="_blank">查看当前文件</a> (<?php echo htmlspecialchars($current_multimedia_path); ?>)
            <?php endif; ?>
            <br>
            <label for="delete_current_multimedia">
                <input type="checkbox" id="delete_current_multimedia" name="delete_current_multimedia" value="1"> 删除当前文件（注意：如同时上传新文件，此选项将被忽略）
            </label>
        <?php else: ?>
            <p>文件路径: <code><?php echo htmlspecialchars($current_multimedia_path); ?></code> (文件未找到)</p>
            <label for="delete_current_multimedia">
                <input type="checkbox" id="delete_current_multimedia" name="delete_current_multimedia" value="1" checked> 确认移除无效文件路径 (推荐)
            </label>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="new_multimedia">上传新多媒体文件 (可选, 替换当前文件):</label>
        <input type="file" id="new_multimedia" name="new_multimedia" accept=".jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.webm">
    </div>
    <div class="form-group">
        <input type="submit" value="更新事件" class="teacher-button">
        <a href="manage_history.php" class="teacher-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
