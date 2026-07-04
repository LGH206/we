<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/SettingController.php';
$db = (new Database())->getConnection();
$settingController = new SettingController($db);
$ai_enabled = $settingController->get('ai_comment_moderation');
$ai_checked = ($ai_enabled == '1') ? 'checked' : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý bình luận — Đời sống sức khoẻ</title>
  <link rel="stylesheet" href="CSS/admin.css" />
  <script src="JavaScript/admin_comment.js" defer></script>
  <script src="JavaScript/nav-link.js" defer></script>
  <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css" />
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
          <a href="Admin_comment.php" class="nav-item active"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
          <a href="Admin_post.php" class="nav-item"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
          <a href="Admin_category.php" class="nav-item"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
        </nav>
      </div>
      <div class="nav nav-footer">
        <button class="nav-item" id="logoutBtn"><i class="icon-log-out"></i><span>Đăng xuất</span></button>
      </div>
    </aside>
    <!-- Main -->
    <div class="main">
      <header class="header">
        <div class="site">
          <div class="site-logo"><img class="admin_icon_img" src="assets/logo.png" alt="logo"/></div>
          <div>
            <h3 class="site-title">Đời sống sức khoẻ</h3>
            <p class="caption muted">Tin tức sức khoẻ hàng ngày</p>
          </div>
        </div>
        <div class="header-actions">
          <div class="search">
            <i class="icon-search"></i>
            <input type="text" placeholder="Tìm kiếm dữ liệu..." id="searchInput" />
          </div>
          <button class="icon-btn"><i class="icon-bell"></i><span class="dot"></span></button>
          <div class="avatar-sm"></div>
        </div>
      </header>
      <main class="content">
        <div class="page-head">
          <h1>Quản lý bình luận</h1>
          <div class="ai-toggle">
            <span class="ai-toggle-label">Tự động điều hành</span>
            <label class="switch">
              <input type="checkbox" id="ai-switch" <?php echo $ai_checked; ?> />
              <span class="slider"></span>
            </label>
            <span class="ai-status" id="ai-status">
              <span class="dot"></span>
              Đại lý AI <?php echo ($ai_enabled == '1') ? 'đang chạy' : 'đã tắt'; ?>
            </span>
          </div>
        </div>
        <!-- KPI cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-label">Tổng bình luận</div>
            <div class="kpi-row">
              <div class="kpi-value" id="totalComments">0</div>
            </div>
          </div>
          <div class="kpi-card kpi-card--danger">
            <div class="kpi-label">Vi phạm bị gắn cờ</div>
            <div class="kpi-row">
              <div class="kpi-value" id="violationCount">0</div>
              <span class="kpi-sub">Cần xử lý</span>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">Đã tự động xử lý</div>
            <div class="kpi-row">
              <div class="kpi-value" id="autoProcessed">0</div>
              <span class="kpi-sub">24 giờ qua</span>
            </div>
          </div>
        </div>
        <!-- Queue table -->
        <div class="table-card queue-card">
          <div class="queue-head">
            <h2 class="queue-title">Hàng đợi Kiểm duyệt</h2>
            <div class="queue-actions">
              <button class="btn-ghost" id="filterBtn"><i class="icon icon-sliders-horizontal"></i> Bộ lọc</button>
              <button class="btn-primary" id="approveAllBtn"><i class="icon icon-check"></i> Phê duyệt tất cả</button>
            </div>
          </div>
          <table class="user-table comment-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Nội dung bình luận</th>
                <th>Bài viết</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="comment-tbody">
              <!-- Load bằng JS -->
            </tbody>
          </table>
          <div class="pagination">
            <span class="pagination-info" id="pagination-info">Đang hiển thị 0 bình luận</span>
            <div class="pagination-pages" id="pagination-pages"></div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script>
    const AI_ENABLED = <?php echo json_encode($ai_enabled); ?>;
  </script>
</body>
</html>