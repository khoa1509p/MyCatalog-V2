<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// XỬ LÝ TÌM KIẾM & FILTER
$keyword = trim($_GET['keyword'] ?? '');
$categoryFilter = (int)($_GET['category'] ?? 0);

// Xây dựng câu SQL linh hoạt dựa trên bộ lọc
$sql = '
    SELECT 
        b.id, b.name, b.author, b.price, b.rating, 
        c.name AS category_name, c.id AS category_id
    FROM books b
    JOIN categories c ON c.id = b.category_id
    WHERE 1=1
';
$params = [];

if ($keyword !== '') {
    $sql .= ' AND b.name LIKE :keyword';
    $params['keyword'] = '%' . $keyword . '%';
}

if ($categoryFilter > 0) {
    $sql .= ' AND b.category_id = :category_id';
    $params['category_id'] = $categoryFilter;
}

$sql .= ' ORDER BY b.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$filteredBooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách Categories cho thẻ <select> ở thanh tìm kiếm
$catStmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
$categories = $catStmt->fetchAll();

$pageTitle = 'Trang chủ - My BookStore';
require __DIR__ . '/includes/header.php';
?>

<div class="container">
    <!-- FORM TÌM KIẾM & LỌC -->
    <form class="search-form" method="GET" action="index.php">
        <input type="text" name="keyword" placeholder="Nhập tên sách..." 
               value="<?= e($keyword) ?>">
        
        <select name="category">
            <option value="0">-- Tất cả thể loại --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoryFilter === (int)$cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
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
                    <span class="badge badge-cat"><?= htmlspecialchars($book['category_name'], ENT_QUOTES, 'UTF-8') ?></span>
                    
                    <a href="detail.php?id=<?= $book['id'] ?>" style="text-decoration: none; color: #2c3e50;">
                     <h3><?= htmlspecialchars($book['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    </a>
                    <p><strong>Tác giả:</strong> <?= htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Đánh giá:</strong> ⭐ <?= $book['rating'] ?>/5.0</p>
                    <p style="color: #e74c3c; font-weight: bold; font-size: 18px;">
                        <?= number_format($book['price'], 0, ',', '.') ?> đ
                    </p>

                    <!-- NÚT SỬA VÀ XÓA ĐƯỢC THÊM VÀO ĐÂY -->
                    <div style="margin-top: 15px; display: flex; gap: 10px;">
                        <a href="edit-item.php?id=<?= $book['id'] ?>" style="padding: 6px 12px; background: #f39c12; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">Sửa</a>
                        <form method="POST" action="delete-item.php" style="margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                            <input type="hidden" name="id" value="<?= $book['id'] ?>">
                            <button type="submit" style="padding: 6px 12px; background: #c0392b; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">Xóa</button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>