<?php
// No HTML output from this script, it's purely for processing.
// Session start and auth check MUST be done first.
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in, could redirect to login or just output an error if accessed directly (though it shouldn't be).
    // For simplicity here, we'll assume header.php in other files handles redirection if someone tries to access a page directly without session.
    // But since this is a direct action script, a check is good.
    header("Location: index.php?error=" . urlencode("请先登录。"));
    exit;
}
require_once 'config.php';

$conn = get_db_connection();
// Connection error and charset are handled by get_db_connection()

$faculty_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($faculty_id <= 0) {
    header("Location: manage_faculty.php?action=error&msg=" . urlencode("无效的教职工ID。"));
    exit;
}

// First, fetch the image_path to delete the file
$image_path_to_delete = null;
$stmt_fetch_image = $conn->prepare("SELECT image_path FROM faculty WHERE id = ?");
if ($stmt_fetch_image) {
    $stmt_fetch_image->bind_param("i", $faculty_id);
    $stmt_fetch_image->execute();
    $result_image = $stmt_fetch_image->get_result();
    if ($result_image->num_rows === 1) {
        $faculty_data = $result_image->fetch_assoc();
        $image_path_to_delete = $faculty_data['image_path'];
    }
    $stmt_fetch_image->close();
} else {
    header("Location: manage_faculty.php?action=error&msg=" . urlencode("获取图片路径失败: " . $conn->error));
    exit;
}

// Delete the record from the database
$stmt_delete = $conn->prepare("DELETE FROM faculty WHERE id = ?");
if ($stmt_delete) {
    $stmt_delete->bind_param("i", $faculty_id);
    if ($stmt_delete->execute()) {
        // If DB deletion was successful, and an image path exists, delete the image file
        if ($image_path_to_delete) {
            $full_image_path = '../' . $image_path_to_delete; // Path relative to current script location (admin/)
            if (file_exists($full_image_path)) {
                if (!unlink($full_image_path)) {
                    // File deletion failed, but DB record is gone. Log this or notify admin.
                    // For now, we'll still count the operation as mostly successful.
                     header("Location: manage_faculty.php?action=deleted&msg=" . urlencode("记录已删除，但图片文件删除失败。请检查文件权限。"));
                     exit;
                }
            }
        }
        header("Location: manage_faculty.php?action=deleted");
        exit;
    } else {
        header("Location: manage_faculty.php?action=error&msg=" . urlencode("删除教职工信息失败: " . $stmt_delete->error));
        exit;
    }
    $stmt_delete->close();
} else {
    header("Location: manage_faculty.php?action=error&msg=" . urlencode("数据库准备语句失败: " . $conn->error));
    exit;
}

$conn->close();
?>
