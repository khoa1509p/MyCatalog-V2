<?php
require_once __DIR__ . '/includes/functions.php';

$name = '';
$author = '';
$category = '';
$price = '';
$errors = [];
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');

    // Validation 1: Tên sách không được để trống
    if ($name === '') {
        $errors['name'] = 'Vui lòng nhập tên sách.';
    }

    // Validation 2: Tác giả không được để trống
    if ($author === '') {
        $errors['author'] = 'Vui lòng nhập tên tác giả.';
    }

    // Validation 3: Giá phải là số và lớn hơn 0
    if ($price === '') {
        $errors['price'] = 'Vui lòng nhập giá tiền.';
    } elseif (!is_numeric($price)) {
        $errors['price'] = 'Giá tiền phải là số.';
    } elseif ((float)$price <= 0) {
        $errors['price'] = 'Giá tiền phải lớn hơn 0.';
    }

    // Validation 4: Thể loại phải hợp lệ
    $allowedCategories = ['Kỹ năng sống', 'Tiểu thuyết', 'Công nghệ', 'Trinh thám'];
    if ($category === '') {
        $errors['category'] = 'Vui lòng chọn thể loại.';
    } elseif (!in_array($category, $allowedCategories)) {
        $errors['category'] = 'Thể loại không hợp lệ.';
    }

    if (empty($errors)) {
        $isSuccess = true;
    }
}

$pageTitle = 'Thêm Sách Mới - My BookStore';
require __DIR__ . '/includes/header.php';
?>

<h2>Thêm Sách Mới</h2>

<?php if ($isSuccess): ?>
    <div class="alert-success">
        <strong>Thêm thành công!</strong> Sách "<?= e($name) ?>" của tác giả <?= e($author) ?> đã được ghi nhận.
    </div>
<?php endif; ?>

<form method="POST" action="create-item.php">
    <div class="form-group">
        <label for="name">Tên sách *</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= e($name) ?>">
        <?php if (isset($errors['name'])): ?>
            <div class="text-danger"><?= e($errors['name']) ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="author">Tác giả *</label>
        <input type="text" id="author" name="author" class="form-control" value="<?= e($author) ?>">
        <?php if (isset($errors['author'])): ?>
            <div class="text-danger"><?= e($errors['author']) ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="category">Thể loại *</label>
        <select id="category" name="category" class="form-control">
            <option value="">-- Chọn thể loại --</option>
            <option value="Kỹ năng sống" <?= $category === 'Kỹ năng sống' ? 'selected' : '' ?>>Kỹ năng sống</option>
            <option value="Tiểu thuyết" <?= $category === 'Tiểu thuyết' ? 'selected' : '' ?>>Tiểu thuyết</option>
            <option value="Công nghệ" <?= $category === 'Công nghệ' ? 'selected' : '' ?>>Công nghệ</option>
            <option value="Trinh thám" <?= $category === 'Trinh thám' ? 'selected' : '' ?>>Trinh thám</option>
        </select>
        <?php if (isset($errors['category'])): ?>
            <div class="text-danger"><?= e($errors['category']) ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="price">Giá bán (VNĐ) *</label>
        <input type="text" id="price" name="price" class="form-control" value="<?= e($price) ?>">
        <?php if (isset($errors['price'])): ?>
            <div class="text-danger"><?= e($errors['price']) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit">Lưu thông tin</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>