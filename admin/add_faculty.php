<?php
$page_title = "添加新教职工";
include 'templates/header.php';
require_once 'config.php';

$conn = get_db_connection();
// Connection error and charset are handled by get_db_connection()

$name = '';
$title = '';
$bio = '';
$error_message = '';
$success_message = '';

// Define upload directory relative to the project root
define('UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT', 'ufiles/faculty/');
// Define upload directory relative to THIS SCRIPT's location for move_uploaded_file
define('UPLOAD_DIR_FROM_SCRIPT', '../' . UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $title = trim($_POST['title']);
    $bio = trim($_POST['bio']);
    $image_path_for_db = null; // Path to store in DB, relative to project root

    // Basic validation
    if (empty($name)) {
        $error_message = "姓名是必填项。";
    } else {
        // File upload handling
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_name = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_type = $_FILES['image']['type'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($image_ext, $allowed_extensions) && in_array($image_type, $allowed_mime_types)) {
                if ($image_size <= 2 * 1024 * 1024) { // Max 2MB
                    // Create unique filename
                    $unique_image_name = time() . '_' . uniqid() . '.' . $image_ext;
                    $target_file_path_from_script = UPLOAD_DIR_FROM_SCRIPT . $unique_image_name;

                    // Ensure upload directory exists and is writable
                    if (!is_dir(UPLOAD_DIR_FROM_SCRIPT)) {
                        if (!mkdir(UPLOAD_DIR_FROM_SCRIPT, 0777, true)) {
                             $error_message = "错误：无法创建上传目录。请检查权限。";
                        }
                    }

                    if (empty($error_message) && is_writable(UPLOAD_DIR_FROM_SCRIPT)) {
                        if (move_uploaded_file($image_tmp_name, $target_file_path_from_script)) {
                            $image_path_for_db = UPLOAD_DIR_RELATIVE_TO_PROJECT_ROOT . $unique_image_name;
                        } else {
                            $error_message = "上传图片失败。";
                        }
                    } elseif(empty($error_message)) {
                         $error_message = "错误：上传目录不可写。请检查权限。";
                    }

                } else {
                    $error_message = "图片大小不能超过2MB。";
                }
            } else {
                $error_message = "只允许上传JPG, JPEG, PNG, GIF格式的图片。";
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors
            $error_message = "图片上传时发生错误，错误代码：" . $_FILES['image']['error'];
        }

        // If no errors so far, proceed to insert into database
        if (empty($error_message)) {
            $stmt = $conn->prepare("INSERT INTO faculty (name, title, bio, image_path) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $name, $title, $bio, $image_path_for_db);
                if ($stmt->execute()) {
                    header("Location: manage_faculty.php?action=added");
                    exit;
                } else {
                    $error_message = "数据库插入失败: " . $stmt->error;
                }
                $stmt->close();
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
<?php if ($success_message): // Should not be reached due to redirect ?>
    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
    <div class="form-group">
        <label for="image">图片 (可选, JPG, PNG, GIF, 最大2MB):</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif">
    </div>
    <div class="form-group">
        <input type="submit" value="添加教职工" class="admin-button">
        <a href="manage_faculty.php" class="admin-button secondary">取消</a>
    </div>
</form>

<?php include 'templates/footer.php'; ?>
