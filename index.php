<?php
// 1.DỮ LIỆU (8 items, có id, name, giá trị số (price, rating), category, author)
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

// 2. XỬ LÝ TÌM KIẾM & FILTER
$keyword = trim($_GET['keyword'] ?? '');
$categoryFilter = $_GET['category'] ?? '';

$filteredBooks = [];
foreach ($books as $book) {
    // Tìm theo tên (Không phân biệt hoa thường)
    $matchKeyword = $keyword === '' || mb_stripos($book['name'], $keyword) !== false;
    
    // Lọc theo danh mục
    $matchCategory = $categoryFilter === '' || $book['category'] === $categoryFilter;

    if ($matchKeyword && $matchCategory) {
        $filteredBooks[] = $book;
    }
}
?>

<?php require 'includes/header.php'; ?>
    
    <main>
        <h1>Đây là trang chủ</h1>
        <!-- Nội dung trang chủ -->
    </main>
</body>
</html>

    <div class="container">
        <!-- FORM TÌM KIẾM & LỌC -->
        <form class="search-form" method="GET" action="">
            <input type="text" name="keyword" placeholder="Nhập tên sách..." 
                   value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
            
            <select name="category">
                <option value="">-- Tất cả thể loại --</option>
                <option value="Kỹ năng sống" <?= $categoryFilter === 'Kỹ năng sống' ? 'selected' : '' ?>>Kỹ năng sống</option>
                <option value="Tiểu thuyết" <?= $categoryFilter === 'Tiểu thuyết' ? 'selected' : '' ?>>Tiểu thuyết</option>
                <option value="Công nghệ" <?= $categoryFilter === 'Công nghệ' ? 'selected' : '' ?>>Công nghệ</option>
                <option value="Trinh thám" <?= $categoryFilter === 'Trinh thám' ? 'selected' : '' ?>>Trinh thám</option>
            </select>
            
            <button type="submit">Tìm & Lọc</button>
            <a href="index.php"><button type="button">Xóa lọc</button></a>
        </form>

        <!-- DANH SÁCH SẢN PHẨM -->
        <div class="book-grid">
            <?php if (empty($filteredBooks)): ?>
                <p>Không tìm thấy quyển sách nào phù hợp.</p>
            <?php else: ?>
                <?php foreach ($filteredBooks as $book): ?>
                    <div class="book-card">
                        <!-- Thẻ IF đổi cách hiển thị: Đánh giá >= 4.7 là Sách HOT -->
                        <?php if ($book['rating'] >= 4.7): ?>
                            <span class="badge badge-hot">🔥 Bán chạy</span>
                        <?php endif; ?>
                        <span class="badge badge-cat"><?= htmlspecialchars($book['category'], ENT_QUOTES, 'UTF-8') ?></span>
                        
                        <a href="detail.php?id=<?= $book['id'] ?>" style="text-decoration: none; color: #2c3e50;">
                         <h3><?= htmlspecialchars($book['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        </a>
                        <p><strong>Tác giả:</strong> <?= htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p><strong>Đánh giá:</strong> ⭐ <?= $book['rating'] ?>/5.0</p>
                        <p style="color: #e74c3c; font-weight: bold; font-size: 18px;">
                            <?= number_format($book['price'], 0, ',', '.') ?> đ
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php require __DIR__ . '/includes/footer.php'; ?>
    