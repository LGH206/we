<?php
    error_reporting(E_ALL); ini_set('display_errors', 1);

    // Nhúng cấu hình cơ sở dữ liệu và bộ điều khiển (Controller)
    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/CategoryController.php';

    $database = new Database();
    $db = $database->getConnection();
    $categoryController = new CategoryController($db);

    // Xử lý hành động xóa danh mục ngay khi nhận yêu cầu GET
    if (isset($_GET['action']) && isset($_GET['id'])) {
        if ($_GET['action'] == 'delete') {
            $categoryController->deleteCategory($_GET['id']);
            header("Location: Admin_category.php");
            exit();
        }
    }

    // ---------------- PHẦN XỬ LÝ PHÂN TRANG & TÌM KIẾM ----------------
    $search = $_GET['search'] ?? null;
    
    // Xác định trang hiện tại
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $limit = 5; // Giới hạn hiển thị 5 dòng/trang đồng bộ với Admin_post
    $offset = ($page - 1) * $limit;

    // Thiết lập truy vấn
    $query = "SELECT c.* FROM categories c WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND (c.name LIKE :search OR c.description LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Tính toán tổng số trang sau khi lọc tìm kiếm
    $countQuery = str_replace("c.*", "COUNT(*) as total", $query);
    $countStmt = $db->prepare($countQuery);
    $countStmt->execute($params);
    $filteredTotal = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($filteredTotal / $limit);

    // Sắp xếp theo ID tăng dần (ASC) và áp dụng Phân trang (LIMIT/OFFSET)
    $query .= " ORDER BY c.id ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lấy tổng số lượng danh mục hệ thống để hiển thị lên thẻ Stat
    $totalCategories = $categoryController->getTotalCategories();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý danh mục — Đời sống sức khoẻ</title>
  <link rel="stylesheet" href="CSS/admin.css" />
  <script src="JavaScript/admin_category.js" defer></script>
  <script src="JavaScript/nav-link.js" defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.css" />
  <style>
    /* Bổ sung style cho cấu trúc phân trang giống hệt Admin_post */
    .pagination-links { display: flex; gap: 6px; margin-top: 15px; justify-content: flex-end; }
    .pagination-links a { padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 4px; color: #4a5568; text-decoration: none; font-size: 13px; transition: all 0.2s; }
    .pagination-links a:hover, .pagination-links a.active { background-color: #22c55e; color: white; border-color: #22c55e; }
    .category-card { background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
    .category-card h3 { margin: 10px 0 5px 0; font-size: 18px; }
    .category-card p { font-size: 13px; color: #718096; margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="app">
    <!-- Sidebar (Đã chuyển toàn bộ link sang đuôi .php) -->
    <aside class="sidebar">
      <div>
        <div class="brand">
          <span class="brand-name">Quản trị viên</span>
        </div>
        <nav class="nav">
          <a href="Admin_profile.php" class="nav-item"><i class="icon-user"></i><span>Profile</span></a>
          <a href="Admin_dashboard.php" class="nav-item"><i class="icon-layout-dashboard"></i><span>Bảng điều khiển</span></a>
          <a href="Admin_user.php" class="nav-item"><i class="icon-users"></i><span>Quản lý người dùng</span></a>
          <a href="Admin_comment.php" class="nav-item"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
          <a href="Admin_post.php" class="nav-item"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
          <a href="Admin_category.php" class="nav-item active"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
        </nav>
      </div>
      <div class="nav nav-footer">
        <button class="nav-item"><i class="icon-log-out"></i><span>Đăng xuất</span></button>
      </div>
    </aside>
    <!-- Main -->
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
          <!-- Chuyển đổi công cụ tìm kiếm sang Form GET thực tế -->
          <form method="GET" action="Admin_category.php" class="search">
            <i class="icon-search"></i>
            <input type="text" name="search" placeholder="Tìm kiếm dữ liệu..." value="<?php echo htmlspecialchars($search ?? ''); ?>" />
          </form>
          <button class="icon-btn">
            <i class="icon-bell"></i>
            <span class="dot"></span>
          </button>
          <div class="avatar-sm"></div>
        </div>
      </header>
      <main class="content">
        <div class="page-head">
          <h1>Quản lý danh mục</h1>
          <!-- Chuyển đổi nút bấm thành liên kết chuyển hướng đến trang thêm mới -->
          <a href="Add_category.php" class="btn btn-primary" id="btnAdd">
            <i class="icon-plus"></i> Thêm danh mục mới
          </a>
        </div>
        <div class="stat-card">
          <span class="stat-label">Tổng danh mục</span>
          <span class="stat-value" id="totalCount"><?php echo number_format($totalCategories, 0, ',', '.'); ?></span>
        </div>
        
        <!-- Recent activity: Bảng dữ liệu chi tiết theo cấu trúc cột gốc -->
        <section class="panel">
          <h2>Hoạt động gần đây</h2>
          <table class="data-table">
            <thead>
              <tr>
                <th>Tên danh mục</th>
                <th>ID danh mục</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="recentBody">
              <?php if (empty($categories)): ?>
                <tr>
                  <td colspan="3" style="text-align: center;" class="muted">Không có danh mục nào được tìm thấy.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                  <tr>
                    <td><strong><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($cat['id']); ?></strong></td>
                    <td>
                      <a href="Edit_category.php?id=<?php echo $cat['id']; ?>" style="color: #3b82f6; text-decoration: none; margin-right: 10px;">Sửa</a>
                      <a href="Admin_category.php?action=delete&id=<?php echo $cat['id']; ?>" 
                         onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')" 
                         style="color: #ef4444; text-decoration: none;">Xóa</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          <!-- Điều hướng phân trang ở cuối bảng dữ liệu -->
          <div class="pagination-links">
              <?php if($page > 1): ?>
                  <a href="Admin_category.php?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search ?? ''); ?>">&laquo; Trước</a>
              <?php endif; ?>
              
              <?php for($i = 1; $i <= $totalPages; $i++): ?>
                  <a href="Admin_category.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>" class="<?php echo $page == $i ? 'active' : ''; ?>">
                      <?php echo $i; ?>
                  </a>
              <?php endfor; ?>

              <?php if($page < $totalPages): ?>
                  <a href="Admin_category.php?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search ?? ''); ?>">Sau &raquo;</a>
              <?php endif; ?>
          </div>
        </section>
      </main>
    </div>
  </div>
</body>
</html>