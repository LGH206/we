<?php
    error_reporting(E_ALL); ini_set('display_errors', 1);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/PostController.php';

    $database = new Database();
    $db = $database->getConnection();
    $postController = new PostController($db);

    // Xử lý hành động xóa bài viết trước khi hiển thị danh sách
    if (isset($_GET['action']) && isset($_GET['id'])) {
        if ($_GET['action'] == 'delete') {
            $postController->deletePost($_GET['id']);
            header("Location: Admin_post.php");
            exit();
        }
    }

    // ---------------- PHẦN XỬ LÝ PHÂN TRANG & SẮP XẾP ID TĂNG DẦN ----------------
    $search = $_GET['search'] ?? null;
    $category_id = $_GET['category_id'] ?? 'all'; // Đổi từ category thành category_id
    
    // Xác định trang hiện tại
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $limit = 5; // Hiển thị đúng 5 bài viết trên 1 trang
    $offset = ($page - 1) * $limit;

    // SỬ DỤNG LEFT JOIN ĐỂ LẤY TÊN DANH MỤC TỪ BẢNG CATEGORIES
    $query = "SELECT p.*, c.name as category_name, 'Admin HealthyCare' as author_name 
              FROM posts p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND (p.title LIKE :search OR p.content LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    // Lọc chính xác theo cột mã số category_id
    if ($category_id !== 'all') {
        $query .= " AND p.category_id = :category_id";
        $params[':category_id'] = $category_id;
    }

    // Lấy tổng số dòng sau khi lọc để tính toán số trang
    $countQuery = "SELECT COUNT(*) as total FROM posts p WHERE 1=1";
    if (!empty($search)) {
        $countQuery .= " AND (p.title LIKE :search OR p.content LIKE :search)";
    }
    if ($category_id !== 'all') {
        $countQuery .= " AND p.category_id = :category_id";
    }
    
    $countStmt = $db->prepare($countQuery);
    $countStmt->execute($params);
    $filteredTotal = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($filteredTotal / $limit);

    // Sắp xếp theo ID nhỏ nhất lên trước (ASC) và Phân trang (LIMIT/OFFSET)
    $query .= " ORDER BY p.id ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPosts = $postController->getTotalPosts();

    // Lấy danh sách danh mục thực tế từ bảng categories phục vụ bộ lọc phễu
    try {
        $catStmt = $db->prepare("SELECT id, name FROM categories ORDER BY name ASC");
        $catStmt->execute();
        $categoriesList = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $categoriesList = [];
    }
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Quản lý bài viết — Đời sống sức khoẻ</title>
        <link rel="stylesheet" href="CSS/admin.css" />
        <script src="JavaScript/nav-link.js" defer></script>
        <link rel="stylesheet" href="https://unpkg.com/lucide-static@latest/font/lucide.css" />

        <style>
            .filter-wrapper { position: relative; display: inline-block; }
            .filter-select { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
            .table th:first-child, .table td:first-child { width: 60px; text-align: center; }
            
            /* Giao diện thanh phân trang */
            .pagination-links { display: flex; gap: 6px; }
            .pagination-links a { padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 4px; color: #4a5568; text-decoration: none; font-size: 13px; transition: all 0.2s; }
            .pagination-links a:hover, .pagination-links a.active { background-color: #22c55e; color: white; border-color: #22c55e; }
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
                        <a href="Admin_post.php" class="nav-item active"><i class="icon-file-text"></i><span>Quản lý bài viết</span></a>
                        <a href="Admin_category.php" class="nav-item"><i class="icon-folder-tree"></i><span>Quản lý danh mục</span></a>
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
                        <div class="site-logo"><img class="admin_icon_img" src="assets/logo.png" alt="logo"/></div>
                        <div>
                            <h3 class="site-title">Đời sống sức khoẻ</h3>
                            <p class="caption muted">Tin tức sức khoẻ hàng ngày</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <form method="GET" action="Admin_post.php" class="search">
                            <input type="text" name="search" placeholder="Tìm kiếm dữ liệu..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                            <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category_id); ?>">
                            <button type="submit"><i class="icon-search"></i></button>
                        </form>
                        <button class="icon-btn"><i class="icon-bell"></i><span class="dot"></span></button>
                        <div class="avatar-sm"></div>
                    </div>
                </header>
                <!-- Content -->
                <main class="content">
                    <div class="content-header">
                        <h2 class="page-title">Quản lý bài viết</h2>
                        <a href="Add_post.php" class="btn btn-primary"><i class="icon icon-plus"></i>Thêm bài viết mới</a>
                    </div>
                    <!-- Stats -->
                    <section class="stats" id="stats">
                        <div class="stat-card">
                            <div>
                                <div class="stat-label">Tổng bài viết hệ thống</div>
                                <div class="stat-value"><?php echo number_format($totalPosts, 0, ',', '.'); ?></div>
                            </div>
                        </div>
                    </section>
                    <!-- Table -->
                    <section class="card">
                        <header class="card-header">
                            <h3 class="card-title">Bài viết gần đây</h3>
                            <div class="card-actions">
                                <!-- Ô HÌNH PHỄU LỌC CATEGORY_ID -->
                                <div class="filter-wrapper">
                                    <button class="icon-btn" title="Lọc theo danh mục"><i class="icon icon-filter"></i></button>
                                    <select class="filter-select" onchange="location = this.value;">
                                        <option value="Admin_post.php?category_id=all&search=<?php echo urlencode($search ?? ''); ?>" <?php echo $category_id == 'all' ? 'selected' : ''; ?>>Tất cả danh mục</option>
                                        <?php foreach ($categoriesList as $cat): ?>
                                            <option value="Admin_post.php?category_id=<?php echo $cat['id']; ?>&search=<?php echo urlencode($search ?? ''); ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </header>
                        <table class="table" id="posts-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề bài viết</th>
                                    <th>Danh mục</th>
                                    <th>Tác giả</th>
                                    <th>Ngày thêm</th>
                                    <th>Nội dung tóm tắt</th>
                                    <th class="t-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="posts">
                                <?php if (empty($posts)): ?>
                                    <tr>
                                        <td colspan="7" class="t-center">Không có bài viết nào ở trang này.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($posts as $post): ?>
                                        <tr id="row-post-<?php echo $post['id']; ?>">
                                            <td class="t-center" style="font-weight: bold; color: #4a5568;">#<?php echo $post['id']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                                            <td>
                                                <!-- Hiển thị nhãn tên danh mục có màu sắc rõ ràng -->
                                                <span class="badge" style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; color: #475569;">
                                                    <?php echo htmlspecialchars($post['category_name'] ?? 'Chưa phân loại'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                Admin<br><span style="font-size: 12px; color: #888;">HealthyCare</span>
                                            </td>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($post['created_at']))); ?></td>
                                            <td><?php echo htmlspecialchars(mb_strimwidth($post['content'] ?? '', 0, 60, '...')); ?></td>
                                            <td class="t-right">
                                                <a href="Edit_post.php?id=<?php echo $post['id']; ?>" class="btn-edit">Sửa</a> | 
                                                <a href="Admin_post.php?action=delete&id=<?php echo $post['id']; ?>"
                                                   onclick="return confirm('Chắc chắn xóa bài viết này?')" style="color: red;">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <!-- FOOTER HIỂN THỊ PHÂN TRANG -->
                        <footer class="card-footer">
                            <span>Hiển thị trang <?php echo $page; ?> / <?php echo $totalPages > 0 ? $totalPages : 1; ?> (Tìm thấy <?php echo $filteredTotal; ?> bài viết)</span>
                            <div class="pagination-links">
                                <?php if($page > 1): ?>
                                    <a href="Admin_post.php?page=<?php echo $page-1; ?>&category_id=<?php echo urlencode($category_id); ?>&search=<?php echo urlencode($search ?? ''); ?>">&laquo; Trước</a>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="Admin_post.php?page=<?php echo $i; ?>&category_id=<?php echo urlencode($category_id); ?>&search=<?php echo urlencode($search ?? ''); ?>" class="<?php echo $page == $i ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if($page < $totalPages): ?>
                                    <a href="Admin_post.php?page=<?php echo $page+1; ?>&category_id=<?php echo urlencode($category_id); ?>&search=<?php echo urlencode($search ?? ''); ?>">Sau &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </footer>
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>