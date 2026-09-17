<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'My BookStore' ?></title>
    <style>
        /* CSS dùng chung */
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 0; }
        header, footer { background: #2c3e50; color: white; text-align: center; padding: 15px; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; }
        button { padding: 10px 15px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        
        /* CSS cho trang chủ (index.php) */
        .search-form { background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; }
        .book-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .badge { display: inline-block; padding: 4px 8px; font-size: 12px; border-radius: 4px; color: white; margin-bottom: 10px; }
        .badge-hot { background: #e74c3c; }
        .badge-cat { background: #3498db; }

        /* CSS cho form thêm sách (create-item.php) */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .text-danger { color: red; font-size: 13px; margin-top: 5px; }
        .alert-success { background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #4CAF50; }
    </style>
</head>
<body>
    <header>
        <h1>📚 Tiệm Sách Của Tôi</h1>
        <nav style="margin-top: 10px;">
            <a href="index.php" style="color: white; margin-right: 15px; text-decoration: none;">🏠 Trang chủ</a>
            <a href="create-item.php" style="color: white; text-decoration: none;">➕ Thêm sách</a>
        </nav>
    </header>
    <div class="container">