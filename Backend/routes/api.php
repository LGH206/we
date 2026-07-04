<?php
// Cấu hình Headers cho phép các ứng dụng Front-end gọi tới (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Xử lý request dạng OPTIONS (Preflight request phản hồi nhanh cho trình duyệt)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lấy thông tin URL và Phương thức Request (POST, GET,...)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Đọc luồng dữ liệu JSON gửi lên từ Fetch API ở JavaScript
$data = json_decode(file_get_contents("php://input"));

require_once __DIR__ . '/../Controllers/AuthController.php';
$authController = new AuthController();

// ĐỊNH TUYẾN (ROUTING)
// 1. Route Đăng ký (Lưu ý sửa lại chuỗi khớp với URL bạn cấu hình ở Front-end)
if (preg_match('/api\/auth\/register$/', $uri) && $method === 'POST') {
    $authController->register($data);
} 
// 2. Route Đăng nhập
elseif (preg_match('/api\/auth\/login$/', $uri) && $method === 'POST') {
    $authController->login($data);
} 
// ========== ROUTE BÌNH LUẬN ==========
if (preg_match('/api\/comments$/', $uri) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/CommentController.php';
    $commentController = new CommentController($db);
    $status = $_GET['status'] ?? 'all';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $comments = $commentController->listComments($status, $limit, $offset);
    $total = $commentController->countComments($status);
    echo json_encode([
        'data' => $comments,
        'total' => $total,
        'page' => $page,
        'limit' => $limit
    ]);
    exit;
}

if (preg_match('/api\/comments$/', $uri) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/CommentController.php';
    require_once __DIR__ . '/../Controllers/SettingController.php';
    $commentController = new CommentController($db);
    $settingController = new SettingController($db);
    
    if (empty($data->post_id) || empty($data->user_id) || empty($data->content)) {
        http_response_code(400);
        echo json_encode(['message' => 'Thiếu thông tin bình luận']);
        exit;
    }

    $ai_enabled = $settingController->get('ai_comment_moderation');
    $status = 'pending';
    if ($ai_enabled == '1') {
        // Kiểm tra từ ngữ nhạy cảm (giả lập)
        $blocked_words = ['xúc phạm', 'spam', 'phản cảm', 'lừa đảo', 'bậy', 'tục tĩu'];
        $content_lower = strtolower($data->content);
        $is_blocked = false;
        foreach ($blocked_words as $word) {
            if (strpos($content_lower, $word) !== false) {
                $is_blocked = true;
                break;
            }
        }
        $status = $is_blocked ? 'rejected' : 'approved';
    }
    
    $result = $commentController->createComment($data->post_id, $data->user_id, $data->content, $status);
    if ($result) {
        http_response_code(201);
        echo json_encode(['message' => 'Bình luận đã được gửi', 'status' => $status]);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Lỗi lưu bình luận']);
    }
    exit;
}

if (preg_match('/api\/comments\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    $id = $matches[1];
    require_once __DIR__ . '/../Controllers/CommentController.php';
    $commentController = new CommentController($db);
    $result = $commentController->deleteComment($id);
    if ($result) {
        echo json_encode(['message' => 'Xóa bình luận thành công']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Lỗi xóa bình luận']);
    }
    exit;
}

if (preg_match('/api\/comments\/(\d+)\/approve$/', $uri, $matches) && $method === 'POST') {
    $id = $matches[1];
    require_once __DIR__ . '/../Controllers/CommentController.php';
    $commentController = new CommentController($db);
    $result = $commentController->approveComment($id);
    if ($result) {
        echo json_encode(['message' => 'Bình luận đã được duyệt']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Lỗi duyệt bình luận']);
    }
    exit;
}

if (preg_match('/api\/comments\/approve-all$/', $uri) && $method === 'POST') {
    require_once __DIR__ . '/../Controllers/CommentController.php';
    $commentController = new CommentController($db);
    $result = $commentController->approveAllPending();
    if ($result) {
        echo json_encode(['message' => 'Đã phê duyệt tất cả bình luận đang chờ']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Lỗi phê duyệt hàng loạt']);
    }
    exit;
}

// ========== ROUTE SETTINGS ==========
if (preg_match('/api\/settings$/', $uri) && $method === 'GET') {
    $key = $_GET['key'] ?? null;
    if (!$key) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing key']);
        exit;
    }
    require_once __DIR__ . '/../Controllers/SettingController.php';
    $settingController = new SettingController($db);
    $value = $settingController->get($key);
    echo json_encode(['key' => $key, 'value' => $value]);
    exit;
}

if (preg_match('/api\/settings$/', $uri) && $method === 'PUT') {
    if (empty($data->key) || !isset($data->value)) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing key or value']);
        exit;
    }
    require_once __DIR__ . '/../Controllers/SettingController.php';
    $settingController = new SettingController($db);
    $result = $settingController->set($data->key, $data->value);
    if ($result) {
        echo json_encode(['message' => 'Cập nhật setting thành công']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Lỗi cập nhật setting']);
    }
    exit;
}

// ========== ROUTE DASHBOARD ==========
if (preg_match('/api\/dashboard$/', $uri) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/DashboardController.php';
    $dashboard = new DashboardController($db);
    $overview = $dashboard->getOverview();
    echo json_encode($overview);
    exit;
}

if (preg_match('/api\/statistics$/', $uri) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/DashboardController.php';
    $dashboard = new DashboardController($db);
    $year = $_GET['year'] ?? date('Y');
    $data = [
        'posts_by_month' => $dashboard->getPostsByMonth($year),
        'users_by_month' => $dashboard->getUsersByMonth($year),
        'comments_by_month' => $dashboard->getCommentsByMonth($year),
        'top_posts' => $dashboard->getTopViewedPosts(5),
        'top_categories' => $dashboard->getTopCategories(5),
        'top_authors' => $dashboard->getTopAuthors(5)
    ];
    echo json_encode($data);
    exit;
}

// Xuất báo cáo
if (preg_match('/api\/dashboard\/report$/', $uri) && $method === 'GET') {
    require_once __DIR__ . '/../Controllers/DashboardController.php';
    $dashboard = new DashboardController($db);
    $overview = $dashboard->getOverview();
    $year = $_GET['year'] ?? date('Y');
    $stats = [
        'overview' => $overview,
        'posts_by_month' => $dashboard->getPostsByMonth($year),
        'users_by_month' => $dashboard->getUsersByMonth($year),
        'comments_by_month' => $dashboard->getCommentsByMonth($year),
        'top_posts' => $dashboard->getTopViewedPosts(5),
        'top_categories' => $dashboard->getTopCategories(5),
        'top_authors' => $dashboard->getTopAuthors(5)
    ];
    header('Content-Disposition: attachment; filename="dashboard_report.json"');
    echo json_encode($stats);
    exit;
}

// Các route khác chưa được định nghĩa
else {
    http_response_code(404);
    echo json_encode(["message" => "Đường dẫn API này không tồn tại trên hệ thống!"]);
}

// Route kiểm tra email
if (preg_match('/api\/auth\/check-email$/', $uri) && $method === 'POST') {
    $authController->checkEmail($data);
    exit;
}

// Route đổi mật khẩu trực tiếp
if (preg_match('/api\\/auth\\/reset-password-direct$/', $uri) && $method === 'POST') {
    $authController->resetPasswordDirect($data);
    exit;
}
?>