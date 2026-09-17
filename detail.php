<?php
// 1. DỮ LIỆU SÁCH 
$books = [
    ['id' => 1, 'name' => 'Đắc Nhân Tâm', 'author' => 'Dale Carnegie', 'category' => 'Kỹ năng sống', 'price' => 85000, 'rating' => 4.9],
    ['id' => 2, 'name' => 'Harry Potter và Hòn đá Phù thủy', 'author' => 'J.K. Rowling', 'category' => 'Tiểu thuyết', 'price' => 120000, 'rating' => 4.8],
    ['id' => 3, 'name' => 'Lập trình PHP cơ bản', 'author' => 'David Sklar', 'category' => 'Công nghệ', 'price' => 150000, 'rating' => 4.2],
    ['id' => 4, 'name' => 'Nhà Giả Kim', 'author' => 'Paulo Coelho', 'category' => 'Tiểu thuyết', 'price' => 75000, 'rating' => 4.7],
    ['id' => 5, 'name' => 'Clean Code', 'author' => 'Robert C. Martin', 'category' => 'Công nghệ', 'price' => 320000, 'rating' => 5.0],
    ['id' => 6, 'name' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu', 'author' => 'Rosie Nguyễn', 'category' => 'Kỹ năng sống', 'price' => 90000, 'rating' => 4.5],
    ['id' => 7, 'name' => 'Sherlock Holmes', 'author' => 'Arthur Conan Doyle', 'category' => 'Trinh thám', 'price' => 110000, 'rating' => 4.6],
    ['id' => 8, 'name' => 'Tâm Lý Học Tội Phạm', 'author' => 'T Stanton Samenow', 'category' => 'Trinh thám', 'price' => 135000, 'rating' => 4.3],
];

// 2. LẤY ID TỪ URL VÀ TÌM SÁCH
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selectedBook = null;

foreach ($books as $book) {
    if ($book['id'] === $id) {
        $selectedBook = $book;
        break; // dừng vòng lặp 
    }
}
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
            <span class="badge-cat"><?= htmlspecialchars($selectedBook['category'], ENT_QUOTES, 'UTF-8') ?></span>
            
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