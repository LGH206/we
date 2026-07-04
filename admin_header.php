<?php
    // Lấy tên file hiện tại để tự động highlight menu tương ứng (class="active")
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title : 'Quản trị viên — Đời sống sức khoẻ'; ?></title>
    <link rel="stylesheet" href="CSS/admin.css" />
    
    <!-- Tích hợp chung các JS cần thiết cho Admin -->
    <script src="JavaScript/script.js" defer></script>
    <script src="JavaScript/admin_dashboard.js" defer></script>
    <script src="JavaScript/admin_user.js" defer></script>
    <script src="JavaScript/admin_comment.js" defer></script>
    <script src="JavaScript/nav-link.js" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css" />
</head>
<body>
    <div class="app">
        <!-- ============ SIDEBAR ============ -->
        <aside class="sidebar">
            <div>
                <div class="brand">
                    <span class="brand-name">Quản trị viên</span>
                </div>
                <nav class="nav">
                    <a href="Admin_profile.php" class="nav-item <?php echo ($current_page == 'Admin_profile.php') ? 'active' : ''; ?>"><i class="icon-user"></i><span>Profile</span></a>
                    <a href="Admin_dashboard.php" class="nav-item <?php echo ($current_page == 'Admin_dashboard.php') ? 'active' : ''; ?>"><i class="icon-layout-dashboard"></i><span>Bảng điều khiển</span></a>
                    <a href="Admin_user.php" class="nav-item <?php echo ($current_page == 'Admin_user.php') ? 'active' : ''; ?>"><i class="icon-users"></i><span>Quản lý người dùng</span></a>
                    <a href="Admin_comment.php" class="nav-item <?php echo ($current_page == 'Admin_comment.php') ? 'active' : ''; ?>"><i class="icon-message-square"></i><span>Quản lý bình luận</span></a>
                    <a href="Admin_article.php" class="nav-item <?php echo ($current_page == 'Admin_article.php') ? 'active' : ''; ?>"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
                    <a href="Admin_category.php" class="nav-item <?php echo ($current_page == 'Admin_category.php') ? 'active' : ''; ?>"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
                </nav>
            </div>
            <div class="nav nav-footer">
                <button class="nav-item"><i class="icon-log-out"></i><span>Đăng xuất</span></button>
            </div>
        </aside>

        <!-- ============ MAIN CONTAINER ============ -->
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
                    <div class="search">
                        <i class="icon-search"></i>
                        <input type="text" placeholder="Tìm kiếm dữ liệu..." />
                    </div>
                    <button class="icon-btn">
                        <i class="icon-bell"></i>
                        <span class="dot"></span>
                    </button>
                    <div class="avatar-sm"></div>
                </div>
            </header>