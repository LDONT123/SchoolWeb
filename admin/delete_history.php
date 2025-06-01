<?php
// No HTML output from this script, it's purely for processing.
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php?error=" . urlencode("请先登录。"));
    exit;
}
require_once 'config.php';

$conn = get_db_connection();
// Connection error and charset are handled by get_db_connection()

$history_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($history_id <= 0) {
    header("Location: manage_history.php?action=error&msg=" . urlencode("无效的校史事件ID。"));
    exit;
}

// First, fetch the multimedia_path to delete the file
$multimedia_path_to_delete = null;
$stmt_fetch_file = $conn->prepare("SELECT multimedia_path FROM school_history WHERE id = ?");
if ($stmt_fetch_file) {
    $stmt_fetch_file->bind_param("i", $history_id);
    $stmt_fetch_file->execute();
    $result_file = $stmt_fetch_file->get_result();
    if ($result_file->num_rows === 1) {
        $history_data = $result_file->fetch_assoc();
        $multimedia_path_to_delete = $history_data['multimedia_path'];
    }
    $stmt_fetch_file->close();
} else {
    header("Location: manage_history.php?action=error&msg=" . urlencode("获取文件路径失败: " . $conn->error));
    exit;
}

// Delete the record from the database
$stmt_delete = $conn->prepare("DELETE FROM school_history WHERE id = ?");
if ($stmt_delete) {
    $stmt_delete->bind_param("i", $history_id);
    if ($stmt_delete->execute()) {
        // If DB deletion was successful, and a file path exists, delete the file
        if ($multimedia_path_to_delete) {
            $full_file_path = '../' . $multimedia_path_to_delete; // Path relative to current script location (admin/)
            if (file_exists($full_file_path)) {
                if (!unlink($full_file_path)) {
                     header("Location: manage_history.php?action=deleted&msg=" . urlencode("记录已删除，但文件删除失败。请检查文件权限。"));
                     exit;
                }
            }
        }
        header("Location: manage_history.php?action=deleted");
        exit;
    } else {
        header("Location: manage_history.php?action=error&msg=" . urlencode("删除校史事件失败: " . $stmt_delete->error));
        exit;
    }
    $stmt_delete->close();
} else {
    header("Location: manage_history.php?action=error&msg=" . urlencode("数据库准备语句失败: " . $conn->error));
    exit;
}

$conn->close();
?>
