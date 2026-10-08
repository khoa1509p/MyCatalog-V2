<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    exit('ID sách không hợp lệ.');
}

// Tìm sách trong CSDL bằng prepared statement
$stmt = $pdo->prepare('
    SELECT b.*, c.name AS category_name 
    FROM books b 
    JOIN categories c ON c.id = b.category_id 
    WHERE b.id = :id
');
$stmt->execute(['id' => $id]);
$selectedBook = $stmt->fetch(PDO::FETCH_ASSOC);

$pageTitle = 'Chi tiết Sách - My BookStore';
require __DIR__ . '/includes/header.php'; 
?>

<?php require 'includes/header.php'; ?>
    
    <main>
        <h1>Chi tiết sản phẩm/bài viết</h1>
        <!-- Nội dung trang chi tiết -->
    </main>
</body>
</html>

    <div class="container">
        <?php if ($selectedBook): ?>
            <!-- NẾU TÌM THẤY SÁCH -->
            <span class="badge-cat"><?= htmlspecialchars($selectedBook['category_name'], ENT_QUOTES, 'UTF-8') ?></span>
            
            <h2><?= htmlspecialchars($selectedBook['name'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><strong>Tác giả:</strong> <?= htmlspecialchars($selectedBook['author'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Mã sản phẩm:</strong> #<?= $selectedBook['id'] ?></p>
            <p><strong>Đánh giá:</strong> ⭐ <?= $selectedBook['rating'] ?> / 5.0</p>
            
            <div class="price"><?= number_format($selectedBook['price'], 0, ',', '.') ?> đ</div>
            
            <a href="index.php" class="btn-back">⬅ Quay lại danh sách</a>
            
        <?php else: ?>
            <!-- NẾU ID KHÔNG TỒN TẠI -->
            <p class="error">❌ Không tìm thấy sách hoặc mã sách không hợp lệ!</p>
            <a href="index.php" class="btn-back">⬅ Quay lại danh sách</a>
        <?php endif; ?>
    <?php require __DIR__ . '/includes/footer.php'; ?>