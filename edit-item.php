<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// 1. Lấy ID từ URL và kiểm tra
$bookId = (int)($_GET['id'] ?? 0);
if ($bookId <= 0) {
    exit('ID sách không hợp lệ.');
}

// 2. Lấy dữ liệu sách hiện tại từ DB
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $bookId]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    exit('Không tìm thấy sách.');
}

// Lấy danh mục để hiển thị thẻ <select>
$catStmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Gán dữ liệu cũ vào biến để pre-fill form
$name = $book['name'];
$author = $book['author'];
$categoryId = (int)$book['category_id'];
$price = $book['price'];
$rating = $book['rating'];
$errors = [];

// 3. Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $categoryId = (int)($_POST['category'] ?? 0);
    $price = trim($_POST['price'] ?? '');
    $rating = trim($_POST['rating'] ?? '');

    // Validation
    if ($name === '') $errors['name'] = 'Vui lòng nhập tên sách.';
    if ($author === '') $errors['author'] = 'Vui lòng nhập tên tác giả.';
    if ($price === '') $errors['price'] = 'Vui lòng nhập giá tiền.';
    elseif (!is_numeric($price)) $errors['price'] = 'Giá tiền phải là số.';
    elseif ((float)$price <= 0) $errors['price'] = 'Giá tiền phải lớn hơn 0.';
    
    if ($rating === '') $errors['rating'] = 'Vui lòng nhập điểm đánh giá.';
    elseif (!is_numeric($rating)) $errors['rating'] = 'Điểm đánh giá phải là số.';
    elseif ((float)$rating < 0 || (float)$rating > 5) $errors['rating'] = 'Điểm đánh giá phải từ 0 đến 5.';

    $validCategoryIds = array_column($categories, 'id');
    if (!in_array($categoryId, $validCategoryIds)) $errors['category'] = 'Vui lòng chọn thể loại hợp lệ.';

    if (empty($errors)) {
        // UPDATE dữ liệu vào CSDL
        $stmtUpdate = $pdo->prepare('
            UPDATE books 
            SET category_id = :category_id, name = :name, author = :author, price = :price, rating = :rating 
            WHERE id = :id
        ');
        $stmtUpdate->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'author' => $author,
            'price' => $price,
            'rating' => (float)$rating,
            'id' => $bookId
        ]);
        
        header('Location: index.php?updated=1');
        exit;
    }
}

$pageTitle = 'Sửa Thông Tin Sách - My BookStore';
require __DIR__ . '/includes/header.php';
?>

<h2>Sửa Thông Tin Sách #<?= $bookId ?></h2>

<form method="POST" action="edit-item.php?id=<?= $bookId ?>">
    <div class="form-group">
        <label>Tên sách *</label>
        <input type="text" name="name" class="form-control" value="<?= e($name) ?>">
        <?php if (isset($errors['name'])): ?><div class="text-danger"><?= e($errors['name']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Tác giả *</label>
        <input type="text" name="author" class="form-control" value="<?= e($author) ?>">
        <?php if (isset($errors['author'])): ?><div class="text-danger"><?= e($errors['author']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Thể loại *</label>
        <select name="category" class="form-control">
            <option value="0">-- Chọn thể loại --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['category'])): ?><div class="text-danger"><?= e($errors['category']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Giá bán (VNĐ) *</label>
        <input type="text" name="price" class="form-control" value="<?= e($price) ?>">
        <?php if (isset($errors['price'])): ?><div class="text-danger"><?= e($errors['price']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Đánh giá (0 - 5 sao) *</label>
        <input type="text" name="rating" class="form-control" value="<?= e($rating) ?>">
        <?php if (isset($errors['rating'])): ?><div class="text-danger"><?= e($errors['rating']) ?></div><?php endif; ?>
    </div>
    <button type="submit" style="background: #f39c12;">Cập nhật thông tin</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>