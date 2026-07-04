<?php
    error_reporting(E_ALL); ini_set('display_errors', 1);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/CategoryController.php';

    $database = new Database();
    $db = $database->getConnection();
    $categoryController = new CategoryController($db);

    $message = '';
    $statusType = '';

    // Xử lý khi Admin nhấn nút Submit Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => $_POST['name'] ?? ''
        ];

        if (!empty($data['name'])) {
            if ($categoryController->addCategory($data)) {
                header("Location: Admin_category.php");
                exit();
            } else {
                $message = "Đã xảy ra lỗi hệ thống, không thể lưu danh mục vào database.";
                $statusType = "error";
            }
        } else {
            $message = "Vui lòng nhập đầy đủ tên danh mục!";
            $statusType = "warning";
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thêm danh mục mới — Đời sống sức khoẻ</title>
  <link rel="stylesheet" href="CSS/admin.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />
  <style>
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #4a5568; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
    .form-control:focus { border-color: #22c55e; outline: none; }
    .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
    .alert-warning { background: #fff9db; color: #f59f00; border: 1px solid #ffe066; }
    .alert-error { background: #fff5f5; color: #fa5252; border: 1px solid #ffc9c9; }
    .btn-group { display: flex; gap: 10px; margin-top: 30px; }
  </style>
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div>
        <div class="brand"><span class="brand-name">Quản trị viên</span></div>
        <nav class="nav">
          <a href="Admin_profile.php" class="nav-item"><i class="icon-user"></i><span>Profile</span></a>
          <a href="Admin_dashboard.php" class="nav-item"><i class="icon-layout-dashboard"></i><span>Bảng điều khiển</span></a>
          <a href="Admin_user.php" class="nav-item"><i class="icon-users"></i><span>Quản lý người dùng</span></a>
          <a href="Admin_comment.php" class="nav-item"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
          <a href="Admin_post.php" class="nav-item"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
          <a href="Admin_category.php" class="nav-item active"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
        </nav>
      </div>
    </aside>
    <!-- Main Content -->
    <div class="main">
      <header class="header">
        <div class="site">
          <div class="site-logo"><img class="admin_icon_img" src="assets/logo.png" alt="logo"/></div>
          <div>
            <h3 class="site-title">Đời sống sức khoẻ</h3>
            <p class="caption muted">Tin tức sức khoẻ hàng ngày</p>
          </div>
        </div>
      </header>
      
      <main class="content">
        <div class="page-head">
          <h1>Thêm danh mục mới</h1>
        </div>

        <section class="card" style="padding: 30px; max-width: 700px; margin: 0 auto;">
          <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $statusType; ?>"><?php echo $message; ?></div>
          <?php endif; ?>

          <form method="POST" action="Add_category.php">
            <!-- Ô nhập liệu Tên danh mục duy nhất tương ứng với Database -->
            <div class="form-group">
              <label for="name">Tên danh mục <span style="color: red;">*</span></label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Ví dụ: Dinh dưỡng, Tập luyện, Sức khoẻ..." required>
            </div>

            <div class="btn-group">
              <button type="submit" class="btn btn-primary"><i class="icon-plus"></i> Lưu danh mục</button>
              <a href="Admin_category.php" class="btn" style="background: #e2e8f0; color: #4a5568; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-size: 14px;">Quay lại</a>
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>
</body>
</html>