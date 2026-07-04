<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db);

// 1. Xử lý logic nút bấm
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'toggle' && isset($_GET['id'])) {
        $userController->toggleStatus($_GET['id'], $_GET['status']);
        header("Location: Admin_user.php"); exit();
    }
    
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $userController->deleteUser($_GET['id']);
        header("Location: Admin_user.php"); exit();
    }

    if ($_GET['action'] == 'add' && isset($_POST['submit_add'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $userModel = new Users($db);
        $result = $userModel->create($fullname, $email, $password, $role);
        
        if ($result === "email_exists") {
            echo "<script>alert('Email đã tồn tại!'); window.location='Admin_user.php';</script>";
        } else {
            header("Location: Admin_user.php");
            exit();
        }
    }
}

// 2. CẤU HÌNH PHÂN TRANG (Hiển thị đúng 10 người dùng trên 1 trang)
$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 3. Lấy số liệu thống kê & Tính toán tổng số trang
$totalUsers = $userController->getTotalUsers(); 
$blockedUsers = $userController->getBlockedUsers();
$totalPages = ceil($totalUsers / $limit);

// Lấy dữ liệu tìm kiếm kèm theo giới hạn phân trang
$search = $_GET['search'] ?? null;
$role = $_GET['role'] ?? 'all';
$users = $userController->listUsers($search, $role, $limit, $offset);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý người dùng — Đời sống sức khoẻ</title>
  <link rel="stylesheet" href="CSS/admin.css" />
  <script src="JavaScript/admin_user.js" defer></script>
  <script src="JavaScript/nav-link.js" defer></script>
  <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css" />
</head>
<body>
  <div class="app">
    <!-- ===== Sidebar ===== -->
    <aside class="sidebar">
      <div>
        <div class="brand">
          <span class="brand-name">Quản trị viên</span>
        </div>
        <nav class="nav">
          <a href="Admin_profile.php" class="nav-item"><i class="icon-user"></i><span>Profile</span></a>
          <a href="Admin_dashboard.php" class="nav-item"><i class="icon-layout-dashboard"></i><span>Bảng điều khiển</span></a>
          <a href="Admin_user.php" class="nav-item active"><i class="icon-users"></i><span>Quản lý người dùng</span></a>
          <a href="Admin_comment.php" class="nav-item"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
          <a href="Admin_post.php" class="nav-item"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
          <a href="Admin_category.php" class="nav-item"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
      </nav>
      </div>
      <div class="nav nav-footer">
        <button class="nav-item"><i class="icon-log-out"></i><span>Đăng xuất</span></button>
      </div>
    </aside>
    <!-- ===== Main ===== -->
    <div class="main">
      <!-- Topbar -->
      <header class="header">
        <div class="site">
          <div class="site-logo">
            <img class="admin_icon_img" src="assets/logo.png" alt="logo"/>
          </div>
          <div>
            <h3 class="site-title">Đời sống sức khoẻ</h3>
            <p class="caption muted">Tin tức sức khoẻ hàng ngày</p>
          </div>
        </div>
        <div class="header-actions">
            <form method="GET" action="Admin_user.php" class="search">
                <input type="text" name="search" placeholder="Tìm kiếm tên, email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit"><i class="icon-search"></i></button>
            </form>
          <button class="icon-btn">
            <i class="icon-bell"></i>
            <span class="dot"></span>
          </button>
          <div class="avatar-sm"></div>
        </div>
      </header>
      <!-- Content -->
      <main class="content">
        <div class="page-head">
          <h1>Quản lý người dùng</h1>
          <div class="stats">
              <div class="stat-card">
                  <div>
                      <div class="stat-label">Tổng người dùng</div>
                      <div class="stat-value"><?php echo $totalUsers; ?></div>
                  </div>
              </div>
              <div class="stat-card">
                  <div>
                      <div class="stat-label">Tài khoản bị khoá</div>
                      <div class="stat-value"><?php echo $blockedUsers; ?></div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Filter bar -->
        <div class="filter-bar">
          <div class="filter-left">
            <span class="filter-label">Bộ lọc nhanh:</span>
            <div class="filter-group" id="filter-group">
                <?php $currentRole = $_GET['role'] ?? 'all'; ?>
                <a href="Admin_user.php?role=all" class="filter-btn <?php echo ($currentRole == 'all') ? 'is-active' : ''; ?>">Tất cả</a>
                <a href="Admin_user.php?role=admin" class="filter-btn <?php echo ($currentRole == 'admin') ? 'is-active' : ''; ?>">Admin</a>
                <a href="Admin_user.php?role=user" class="filter-btn <?php echo ($currentRole == 'user') ? 'is-active' : ''; ?>">Độc giả</a>
            </div>
          </div>
            <a href="Add_user.php" class="btn-primary">
                <i class="icon" data-icon="plus"></i>
                Thêm người dùng mới
            </a>
        </div>
        <!-- Table -->
        <div class="table-card">
          <table class="user-table">
            <thead>
              <tr>
                <th>STT</th>
                <th>Ảnh đại diện</th>
                <th>Họ và Tên</th>
                <th>Địa chỉ Email</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="user-tbody">
                <?php 
                // ĐÃ SỬA: STT tự động lũy tiến dựa vào Offset của trang hiện tại
                $stt = $offset + 1; 
                foreach ($users as $user): 
                ?>
                    <tr>
                    <td>#<?php echo $stt++; ?></td>
                    <td><div class="avatar-sm"></div></td>
                    <td><strong><?php echo htmlspecialchars($user['fullname']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $user['status']; ?>">
                        <?php echo ($user['status'] == 'active') ? 'Đang hoạt động' : 'Bị khóa'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="Admin_user.php?action=toggle&id=<?php echo $user['id']; ?>&status=<?php echo $user['status']; ?>">
                        <?php echo ($user['status'] == 'active') ? 'Khóa' : 'Mở khóa'; ?>
                        </a>
                        <a href="Admin_user.php?action=delete&id=<?php echo $user['id']; ?>" 
                        onclick="return confirm('Chắc chắn xóa?')">Xóa</a>
                        <a href="Edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">Sửa</a>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
          
          <!-- Thanh điều hướng chuyển trang (Pagination Links) -->
          <div class="pagination">
            <span class="pagination-info" id="pagination-info">
                Hiển thị dòng <?php echo ($offset + 1); ?> - <?php echo min($offset + $limit, $totalUsers); ?> trên <?php echo $totalUsers; ?> người dùng
            </span>
            <div class="pagination-pages" id="pagination-pages">
                <!-- Nút lùi trang -->
                <?php if($page > 1): ?>
                    <a href="Admin_user.php?page=<?php echo ($page - 1); ?>&role=<?php echo $role; ?>&search=<?php echo urlencode($search ?? ''); ?>" class="page-link">&laquo; Trước</a>
                <?php endif; ?>

                <!-- Danh sách các trang dựa trên tổng số lượng chia 10 -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="Admin_user.php?page=<?php echo $i; ?>&role=<?php echo $role; ?>&search=<?php echo urlencode($search ?? ''); ?>" class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Nút tiến trang -->
                <?php if($page < $totalPages): ?>
                    <a href="Admin_user.php?page=<?php echo ($page + 1); ?>&role=<?php echo $role; ?>&search=<?php echo urlencode($search ?? ''); ?>" class="page-link">Sau &raquo;</a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>
</html>