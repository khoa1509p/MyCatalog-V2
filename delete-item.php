<?php
require_once __DIR__ . '/config/database.php';

// Chỉ cho phép xóa thông qua nút bấm (phương thức POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Phương thức không hợp lệ. Vui lòng quay lại trang chủ.');
}

$bookId = (int)($_POST['id'] ?? 0);

if ($bookId > 0) {
    // Thực thi lệnh DELETE
    $stmt = $pdo->prepare('DELETE FROM books WHERE id = :id');
    $stmt->execute(['id' => $bookId]);
}

// Xóa xong quay về trang chủ
header('Location: index.php?deleted=1');
exit;