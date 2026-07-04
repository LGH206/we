<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'suckhoe');

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Không thể kết nối đến máy chủ.']));
}

// ---------------------------------------------------------------------------
// XỬ LÝ CÁC YÊU CẦU AJAX (POST) TỪ forget_password.js
// action=check_email      -> kiểm tra email có tồn tại trong hệ thống không
// action=reset_password   -> xác minh mật khẩu cũ rồi đổi sang mật khẩu mới
// ---------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST['action'] ?? '';

    // ---------- 1. KIỂM TRA EMAIL ----------
    if ($action === 'check_email') {
        $email = trim($_POST['email'] ?? '');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ.']);
            exit();
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email này chưa được đăng ký.']);
        }
        $stmt->close();
        exit();
    }

    // ---------- 2. ĐỔI MẬT KHẨU ----------
    if ($action === 'reset_password') {
        $email = trim($_POST['email'] ?? '');
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$email || !$oldPassword || !$newPassword || !$confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới nhập lại không khớp.']);
            exit();
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự.']);
            exit();
        }

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'Email không tồn tại.']);
            exit();
        }

        if (!password_verify($oldPassword, $row['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu cũ không đúng.']);
            exit();
        }

        if ($oldPassword === $newPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới phải khác mật khẩu cũ.']);
            exit();
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $newHash, $row['id']);
        $update->execute();
        $update->close();

        echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Quên mật khẩu</title>
        <link rel="stylesheet" href="CSS/forget_password.css" />
        <script src="JavaScript/forget_password.js" defer></script>
    </head>
    <body>

        <div class="container">
            <img src="assets/logo.png" class="logo" alt="Logo"> <h2>Quên mật khẩu</h2>
            
            <div id="email-step">
                <input type="email" id="email" placeholder="Email đăng ký" required>
                <button onclick="checkEmail()">Kiểm tra Email</button>
            </div>

            <div id="password-step" style="display:none;">
                <input type="password" id="old_password" placeholder="Mật khẩu cũ" required>
                <input type="password" id="new_password" placeholder="Mật khẩu mới" required>
                <input type="password" id="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                <button onclick="submitReset()">Xác nhận đổi mật khẩu</button>
            </div>

            <p id="message"></p>
        </div>
    </body>
</html>