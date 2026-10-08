<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Lấy danh mục từ DB để đổ vào thẻ <select>
$catStmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$name = '';
$author = '';
$categoryId = 0;
$price = '';
$rating = ''; // Thêm biến rating
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $categoryId = (int)($_POST['category'] ?? 0);
    $price = trim($_POST['price'] ?? '');
    $rating = trim($_POST['rating'] ?? ''); // Lấy dữ liệu rating

    // Validation
    if ($name === '') $errors['name'] = 'Vui lòng nhập tên sách.';
    if ($author === '') $errors['author'] = 'Vui lòng nhập tên tác giả.';
    
    if ($price === '') {
        $errors['price'] = 'Vui lòng nhập giá tiền.';
    } elseif (!is_numeric($price)) {
        $errors['price'] = 'Giá tiền phải là số.';
    } elseif ((float)$price <= 0) {
        $errors['price'] = 'Giá tiền phải lớn hơn 0.';
    }

    // Điều kiện cho Rating
    if ($rating === '') {
        $errors['rating'] = 'Vui lòng nhập điểm đánh giá.';
    } elseif (!is_numeric($rating)) {
        $errors['rating'] = 'Điểm đánh giá phải là số.';
    } elseif ((float)$rating < 0 || (float)$rating > 5) {
        $errors['rating'] = 'Điểm đánh giá phải từ 0 đến 5.';
    }

    $validCategoryIds = array_column($categories, 'id');
    if (!in_array($categoryId, $validCategoryIds)) {
        $errors['category'] = 'Vui lòng chọn thể loại hợp lệ.';
    }

    if (empty($errors)) {
        // INSERT vào CSDL bằng prepared statement
        $stmt = $pdo->prepare('
            INSERT INTO books (category_id, name, author, price, rating) 
            VALUES (:category_id, :name, :author, :price, :rating)
        ');
        $stmt->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'author' => $author,
            'price' => $price,
            'rating' => (float)$rating // Đã thay thế số 5.0 bằng biến nhập vào
        ]);
        
        // Chuyển hướng về trang chủ kèm thông báo
        header('Location: index.php?created=1');
        exit;
    }
}

$pageTitle = 'Thêm Sách Mới - My BookStore';
require __DIR__ . '/includes/header.php';
?>

<h2>Thêm Sách Mới</h2>

<form method="POST" action="create-item.php">
    <div class="form-group">
        <label for="name">Tên sách *</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= e($name) ?>">
        <?php if (isset($errors['name'])): ?><div class="text-danger"><?= e($errors['name']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="author">Tác giả *</label>
        <input type="text" id="author" name="author" class="form-control" value="<?= e($author) ?>">
        <?php if (isset($errors['author'])): ?><div class="text-danger"><?= e($errors['author']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="category">Thể loại *</label>
        <select id="category" name="category" class="form-control">
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
        <label for="price">Giá bán (VNĐ) *</label>
        <input type="text" id="price" name="price" class="form-control" value="<?= e($price) ?>">
        <?php if (isset($errors['price'])): ?><div class="text-danger"><?= e($errors['price']) ?></div><?php endif; ?>
    </div>

    <!-- Thêm ô nhập Rating -->
    <div class="form-group">
        <label for="rating">Đánh giá (0 - 5 sao) *</label>
        <input type="text" id="rating" name="rating" class="form-control" value="<?= e($rating) ?>" placeholder="Ví dụ: 4.5">
        <?php if (isset($errors['rating'])): ?><div class="text-danger"><?= e($errors['rating']) ?></div><?php endif; ?>
    </div>

    <button type="submit">Lưu thông tin</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>