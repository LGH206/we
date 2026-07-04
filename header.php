<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/web');

$user_info = [
    'logged_in' => isset($_SESSION['user_id']),
    'fullname'  => $_SESSION['fullname'] ?? '',
    'role'      => $_SESSION['role'] ?? 'guest' 
];

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title) ? $page_title : 'Đời sống sức khoẻ - Tin tức sức khoẻ mới nhất'; ?></title>
    <!-- Đã thêm /web/ vào để trình duyệt luôn tìm đúng gốc -->
    <link rel="stylesheet" href="/web/CSS/main.css" />
    <script>
        window.SERVER_SESSION = {
            loggedIn: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>,
            id: <?php echo json_encode($_SESSION['user_id'] ?? null); ?>,
            fullname: <?php echo json_encode($_SESSION['fullname'] ?? null); ?>,
            role: <?php echo json_encode($_SESSION['role'] ?? null); ?>
        };
    </script>
    <script src="/web/JavaScript/home.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header class="site-header">
        <nav class="navbar" aria-label="Main navigation">
            <div class="navbar__inner">
                <a href="/web/Trangchu.php" class="navbar__brand" aria-label="Trang chủ">
                    <img src="/web/assets/logo.png" alt="HealthyCare News" class="navbar__logo" />
                    <div class="navbar__brand-text">
                        <span class="navbar__brand-name">Đời sống sức khoẻ</span>
                        <span class="navbar__brand-tagline">Tin tức sức khoẻ hàng ngày</span>
                    </div>
                </a>

                <div class="navbar__nav">
                    <a href="/web/Trangchu.php" class="<?php echo ($current_page == 'Trangchu.php') ? 'active' : ''; ?>">Trang chủ</a>
                    <a href="/web/TinTucYTe.php" class="<?php echo ($current_page == 'TinTucYTe.php') ? 'active' : ''; ?>">Tin tức y tế</a>
                    <a href="/web/TinhTrangSucKhoe.php" class="<?php echo ($current_page == 'TinhTrangSucKhoe.php' || $current_page == 'TimMachTuanHoan.php') ? 'active' : ''; ?>">Tình trạng sức khoẻ</a>
                    <a href="/web/LoiSongKhoe.php" class="<?php echo ($current_page == 'LoiSongKhoe.php') ? 'active' : ''; ?>">Lối sống khoẻ</a>
                    <a href="/web/SoCuu.php" class="<?php echo ($current_page == 'SoCuu.php') ? 'active' : ''; ?>">Sơ cứu</a>
                </div>

                <div class="navbar__search" id="searchBox">
                    <input class="search_input" id="searchInput" type="search" placeholder="Nhập từ khóa tìm kiếm...">
                    <button class="navbar__search--btn" id="searchToggleBtn" aria-label="Tìm kiếm">
                        <img class="search_img" src="/web/assets/search.png" alt="tìm kiếm"/>
                    </button>
                </div>
                
                <div class="navbar__actions">
                    <?php
                        $displayName = !empty($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Thành viên';
                    ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="navbar__user" id="userMenu">
                            <button class="navbar__user-btn" id="userMenuToggle">
                                <span class="navbar__avatar" id="userAvatar">
                                    <?php echo mb_substr($displayName, 0, 1, "UTF-8"); ?>
                                </span>
                                <span class="navbar__user-name" id="userName">
                                    <?php echo htmlspecialchars($displayName); ?>
                                </span>
                                <img class="user_symbol" src="/web/assets/arrow.png" alt="mũi tên"/>
                            </button>
                            <div class="navbar__user-menu">
                                <a href="/web/Profile.php">Tài khoản của tôi</a>
                                <a href="/web/DangNhap.php?action=logout" class="logout">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/web/DangNhap.php" class="navbar__login-btn" id="loginBtn">
                            <img class="user_img" src="/web/assets/user.png" alt="người dùng"/>
                            Đăng nhập
                        </a>
                    <?php endif; ?>

                    <button class="navbar__hamburger" aria-label="Menu ">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>