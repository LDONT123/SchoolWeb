<?php
$page_title = "编辑教职工信息";
include 'templates/header.php';
require_once 'config.php';

$conn = get_db_connection();
// Connection error and charset are handled by get_db_connection()

$faculty_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$name = '';
$title = '';
$bio = '';
$current_image_path = '';
$error_message = '';
$success_message = '';

// Define upload directory relative to the project root
define('UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT', 'ufiles/faculty/');
// Define upload directory relative to THIS SCRIPT's location for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT', '../' . UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT);


if ($faculty_id <= 0) {
    header("Location: manage_faculty.php?action=error&msg=" . urlencode("无效的教职工ID。"));
    exit;
}

// Fetch existing data
$stmt_fetch = $conn->prepare("SELECT name, title, bio, image_path FROM faculty WHERE id = ?");
if ($stmt_fetch) {
    $stmt_fetch->bind_param("i", $faculty_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($result->num_rows === 1) {
        $faculty = $result->fetch_assoc();
        $name = $faculty['name'];
        $title = $faculty['title'];
        $bio = $faculty['bio'];
        $current_image_path = $faculty['image_path'];
    } else {
        header("Location: manage_faculty.php?action=error&msg=" . urlencode("未找到指定的教职工信息。"));
        exit;
    }
    $stmt_fetch->close();
} else {
    die("<p class='error-message'>获取教职工信息失败: " . $conn->error . "</p>");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $title = trim($_POST['title']);
    $bio = trim($_POST['bio']);
    $new_image_path_for_db = $current_image_path; // Assume current image path unless new one is uploaded

    if (empty($name)) {
        $error_message = "姓名是必填项。";
    } else {
        // File upload handling for new image
        if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == UPLOAD_ERR_OK) {
            $image_tmp_name = $_FILES['new_image']['tmp_name'];
            $image_name = $_FILES['new_image']['name'];
            $image_size = $_FILES['new_image']['size'];
            $image_type = $_FILES['new_image']['type'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($image_ext, $allowed_extensions) && in_array($image_type, $allowed_mime_types)) {
                if ($image_size <= 2 * 1024 * 1024) { // Max 2MB
                    $unique_image_name = time() . '_' . uniqid() . '.' . $image_ext;
                    $target_file_path_from_script = UPLOAD_DIR_FROM_SCRIPT . $unique_image_name;

                    if (!is_dir(UPLOAD_DIR_FROM_SCRIPT)) {
                        mkdir(UPLOAD_DIR_FROM_SCRIPT, 0777, true);
                    }

                    if (is_writable(UPLOAD_DIR_FROM_SCRIPT)) {
                        if (move_uploaded_file($image_tmp_name, $target_file_path_from_script)) {
                            // New image uploaded successfully, delete old one if exists
                            if ($current_image_path && file_exists('../' . $current_image_path)) {
                                unlink('../' . $current_image_path);
                            }
                            $new_image_path_for_db = UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT . $unique_image_name;
                        } else {
                            $error_message = "上传新图片失败。";
                        }
                    } else {
                         $error_message = "错误：上传目录不可写。";
                    }
                } else {
                    $error_message = "新图片大小不能超过2MB。";
                }
            } else {
                $error_message = "只允许上传JPG, JPEG, PNG, GIF格式的新图片。";
            }
        } elseif (isset($_FILES['new_image']) && $_FILES['new_image']['error'] != UPLOAD_ERR_NO_FILE) {
            $error_message = "新图片上传时发生错误，错误代码：" . $_FILES['new_image']['error'];
        } elseif (isset($_POST['delete_current_image']) && $_POST['delete_current_image'] == '1' && !(isset($_FILES['new_image']) && $_FILES['new_image']['error'] == UPLOAD_ERR_OK && $new_image_path_for_db != $current_image_path) ) {
            // Only process deletion if no new image was successfully uploaded to replace the old one
            if ($current_image_path && file_exists('../' . $current_image_path)) {
                if (unlink('../' . $current_image_path)) {
                    $current_image_path = null; // Clear current path as file is deleted
                } else {
                    $error_message .= "无法删除旧图片。请检查权限。";
                }
            }
            $new_image_path_for_db = null;
        }


        if (empty($error_message)) {
            $stmt_update = $conn->prepare("UPDATE faculty SET name = ?, title = ?, bio = ?, image_path = ? WHERE id = ?");
            if ($stmt_update) {
                $stmt_update->bind_param("ssssi", $name, $title, $bio, $new_image_path_for_db, $faculty_id);
                if ($stmt_update->execute()) {
                    header("Location: manage_faculty.php?action=updated");
                    exit;
                } else {
                    $error_message = "数据库更新失败: " . $stmt_update->error;
                }
                $stmt_update->close();
            } else {
                 $error_message = "数据库准备语句失败: " . $conn->error;
            }
        }
    }
}
$conn->close();
?>

<h2><?php echo $page_title; ?> (ID: <?php echo $faculty_id; ?>)</h2>

<?php if ($error_message): ?>
    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $faculty_id; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">姓名 (必填):</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    <div class="form-group">
        <label for="title">职称:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
    </div>
    <div class="form-group">
        <label for="bio">简介:</label>
        <textarea id="bio" name="bio" rows="6"><?php echo htmlspecialchars($bio); ?></textarea>
    </div>

    <?php if ($current_image_path && file_exists('../' . $current_image_path)): ?>
    <div class="form-group">
        <label>当前图片:</label>
        <img src="../<?php echo htmlspecialchars($current_image_path); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="max-width: 200px; height: auto; margin-bottom: 10px; display:block;">
        <label for="delete_current_image">
            <input type="checkbox" id="delete_current_image" name="delete_current_image" value="1"> 删除当前图片
        </label>
    </div>
    <?php elseif ($current_image_path): ?>
    <div class="form-group">
        <label>当前图片路径 (文件未找到):</label>
        <p><code><?php echo htmlspecialchars($current_image_path); ?></code></p>
        <p class="error-message">注意：图片文件在指定路径未找到。如果保存，此路径将被清空，除非上传新图片。</p>
         <label for="delete_current_image">
            <input type="checkbox" id="delete_current_image" name="delete_current_image" value="1" checked> 确认移除无效图片路径 (推荐)
        </label>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="new_image">上传新图片 (可选, 替换当前图片, JPG, PNG, GIF, 最大2MB):</label>
        <input type="file" id="new_image" name="new_image" accept=".jpg,.jpeg,.png,.gif">
    </div>
    <div class="form-group">
        <input type="submit" value="更新信息" class="admin-button">
        <a href="manage_faculty.php" class="admin-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
