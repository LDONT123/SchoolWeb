<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php?error=" . urlencode("请先登录。"));
    exit;
}
require_once 'config.php'; // Database connection

$conn = get_db_connection();

$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($news_id <= 0) {
    header("Location: manage_news.php?action=error&msg=" . urlencode("无效的新闻ID。"));
    exit;
}

// First, fetch the multimedia_path to delete the file
$multimedia_path_to_delete = null;
$stmt_fetch_file = $conn->prepare("SELECT multimedia_path FROM campus_news WHERE id = ?");
if ($stmt_fetch_file) {
    $stmt_fetch_file->bind_param("i", $news_id);
    $stmt_fetch_file->execute();
    $result_file = $stmt_fetch_file->get_result();
    if ($result_file->num_rows === 1) {
        $news_data = $result_file->fetch_assoc();
        $multimedia_path_to_delete = $news_data['multimedia_path'];
    }
    $stmt_fetch_file->close();
} else {
    // Log error or handle
    header("Location: manage_news.php?action=error&msg=" . urlencode("获取文件路径失败: " . $conn->error));
    exit;
}

// Delete the record from the database
$stmt_delete = $conn->prepare("DELETE FROM campus_news WHERE id = ?");
if ($stmt_delete) {
    $stmt_delete->bind_param("i", $news_id);
    if ($stmt_delete->execute()) {
        // If DB deletion was successful, and a file path exists, delete the file
        if ($multimedia_path_to_delete) {
            // Path is stored relative to project root, e.g., ufiles/news/file.jpg
            // Script is in admin/, so path to file is ../ufiles/news/file.jpg
            $full_file_path = '../' . $multimedia_path_to_delete;
            if (file_exists($full_file_path)) {
                if (!unlink($full_file_path)) {
                     // File deletion failed, but DB record is gone.
                     header("Location: manage_news.php?action=deleted&msg=" . urlencode("记录已删除，但多媒体文件删除失败。请检查文件权限。"));
                     exit;
                }
            }
        }
        header("Location: manage_news.php?action=deleted");
        exit;
    } else {
        header("Location: manage_news.php?action=error&msg=" . urlencode("删除新闻文章失败: " . $stmt_delete->error));
        exit;
    }
    $stmt_delete->close();
} else {
    header("Location: manage_news.php?action=error&msg=" . urlencode("数据库准备语句失败: " . $conn->error));
    exit;
}

$conn->close();
?>
