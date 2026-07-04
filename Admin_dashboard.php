<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/DashboardController.php';
$db = (new Database())->getConnection();
$dashboard = new DashboardController($db);
$overview = $dashboard->getOverview();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bảng điều khiển · Quản trị viên</title>
  <link rel="stylesheet" href="CSS/admin.css" />
  <script src="JavaScript/admin_dashboard.js" defer></script>
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
          <a href="Admin_dashboard.php" class="nav-item active"><i class="icon-layout-dashboard"></i><span>Bảng điều khiển</span></a>
          <a href="Admin_user.php" class="nav-item"><i class="icon-users"></i><span>Quản lý người dùng</span></a>
          <a href="Admin_comment.php" class="nav-item"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
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
            <input type="text" placeholder="Tìm kiếm dữ liệu..." />
          </div>
          <button class="icon-btn"><i class="icon-bell"></i><span class="dot"></span></button>
          <div class="avatar-sm"></div>
        </div>
      </header>
      <main class="content dashboard">
        <header class="page-head">
          <h1>Tổng quan Hệ thống</h1>
          <p class="muted">Các chỉ số hiệu suất và phân tích mức độ tương tác của người dùng theo thời gian thực.</p>
        </header>
        <!-- STATS -->
        <section class="stats">
          <div class="stat-card">
            <span class="stat-label">Tổng người dùng</span>
            <span class="stat-value" id="totalUsers"><?php echo number_format($overview['total_users']); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Tổng bài viết</span>
            <span class="stat-value" id="totalPosts"><?php echo number_format($overview['total_posts']); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Tổng bình luận</span>
            <span class="stat-value" id="totalComments"><?php echo number_format($overview['total_comments']); ?></span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Tổng danh mục</span>
            <span class="stat-value" id="totalCategories"><?php echo number_format($overview['total_categories']); ?></span>
          </div>
        </section>
        <!-- CHART -->
        <section class="card chart-card">
          <header class="chart-head">
            <div>
              <h2>Tổng quan Lưu lượng</h2>
              <p class="caption muted">Số bài viết, người dùng, bình luận theo tháng</p>
            </div>
            <div class="seg" role="tablist">
              <button class="seg-btn active" data-range="week">Hàng tuần</button>
              <button class="seg-btn" data-range="month">Hàng tháng</button>
            </div>
          </header>
          <div class="dashboad__chart-wrap">
            <canvas id="dashboardChart" width="800" height="260"></canvas>
            <div class="chart-axis">
              <span>Tháng 1</span><span>Tháng 6</span><span>Tháng 12</span>
            </div>
          </div>
        </section>
        <!-- POST NOW & REPORT -->
        <section class="post-now">
          <h2>Đăng bài ngay</h2>
          <p class="post-sub">Mang những kiến thức y khoa của bạn đến với cộng đồng trong vài giây.</p>
          <a href="Add_post.php" class="cta cta-light"><i class="icon-plus-circle"></i><span>Tạo bài viết mới</span></a>
          <a href="Admin_post.php" class="cta cta-dark"><i class="icon-list"></i><span>Danh sách các bài viết</span></a>
          <div class="post-footer">
            <span class="caption">Đang chờ duyệt</span>
            <span class="badge-count" id="pendingComments">0</span>
          </div>
        </section>
        <button class="report-btn" id="reportBtn">Tạo báo cáo tổng quan</button>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>