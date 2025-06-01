<?php
$page_title = "Token管理";
include 'templates/header.php'; // Session start and auth check are in header.php
require_once 'config.php';

$conn_manage = get_db_connection();
// Connection error and charset are handled by get_db_connection()

$generate_message = '';
$new_token_value = '';

// Handle Token Generation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_token'])) {
    try {
        $new_token_value = bin2hex(random_bytes(16)); // Generate a 32-character hex token
        $status = 'active'; // Default status for new tokens

        $stmt = $conn_manage->prepare("INSERT INTO tokens (token_value, status) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $new_token_value, $status);
            if ($stmt->execute()) {
                $generate_message = "成功生成新Token！请妥善保管。";
            } else {
                $generate_message = "错误：无法生成Token。" . $stmt->error;
                $new_token_value = '';
            }
            $stmt->close();
        } else {
            $generate_message = "数据库准备语句失败: " . $conn_manage->error;
            $new_token_value = '';
        }
    } catch (Exception $e) {
        $generate_message = "生成Token时发生意外错误：" . $e->getMessage();
        $new_token_value = '';
    }
}

// Fetch existing tokens
$tokens_result = $conn_manage->query("SELECT id, token_value, status, created_at, last_used_at, last_used_by_name FROM tokens ORDER BY created_at DESC");

?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<div class="admin-section">
    <h3>生成新Token</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="generate_token" value="生成新Token" class="admin-button">
    </form>
    <?php if ($generate_message): ?>
        <p class="message <?php echo (strpos($generate_message, '错误') !== false || strpos($generate_message, 'Error') !== false) ? 'error-message' : 'success-message'; ?>">
            <?php echo htmlspecialchars($generate_message); ?>
        </p>
    <?php endif; ?>
    <?php if ($new_token_value): ?>
        <p><strong>新生成的Token:</strong> <code class="token-display"><?php echo htmlspecialchars($new_token_value); ?></code> (请立即复制并妥善保存此Token，它只显示一次！)</p>
    <?php endif; ?>
</div>

<div class="admin-section">
    <h3>现有Token列表</h3>
    <?php if ($tokens_result && $tokens_result->num_rows > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Token值</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>最后使用时间</th>
                    <th>最后使用者</th>
                    <!-- Add action column if needed e.g. for deactivating tokens -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $tokens_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><code><?php echo htmlspecialchars($row['token_value']); ?></code></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo $row['last_used_at'] ? htmlspecialchars($row['last_used_at']) : '从未使用'; ?></td>
                        <td><?php echo $row['last_used_by_name'] ? htmlspecialchars($row['last_used_by_name']) : 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>当前没有已生成的Token。</p>
    <?php endif; ?>
</div>

<?php
if (isset($conn_manage)) {
    $conn_manage->close();
}
include 'templates/footer.php';
?>
