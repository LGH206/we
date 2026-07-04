<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'suckhoe');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Không thể kết nối đến máy chủ.']));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Lấy thêm fullname (và role nếu bảng users có cột này) để lưu vào session
    $stmt = $conn->prepare("SELECT id, password, fullname, email, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Lưu đầy đủ thông tin vào session để header.php nhận diện được trạng thái đăng nhập
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['email']    = $row['email'];
            $_SESSION['role']     = $row['role'] ?? 'user';

            echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sai mật khẩu.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email không tồn tại.']);
    }
    $stmt->close();
    exit();
}

// Xử lý đăng xuất: huỷ session PHP khi truy cập DangNhap.php?action=logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: /web/Trangchu.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đời sống sức khoẻ</title>
    <meta name="description" content="Đăng nhập vào cộng đồng Sống Khỏe để theo dõi sức khỏe và kết nối." />
    <link rel="stylesheet" href="CSS/main.css" />
    <script src="JavaScript/login.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet" />
  </head>
  <body>
    <main class="login_page">
      <div class="login_card">
        <div class="login_header">
          <div class="login_logo">
            <img class="logo_img" src="assets/logo.png" alt="Avatar của bạn">
          </div>
          <h1 class="login_title">Đăng nhập</h1>
          <p class="login_subtitle">Chào mừng trở lại cộng đồng</p>
        </div>

        <form class="form" id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
          <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="nhap@mail.com" />
          </div>

          <div class="field">
            <label for="password">Mật khẩu</label>
            <div class="password-wrap">
              <input type="password" id="password" name="password" placeholder="••••••••" />
              <button type="button" class="toggle-pw" id="togglePw" aria-label="Hiện mật khẩu">
                <span id="eyeIcon">👁️</span>
              </button>
            </div>
          </div>

          <div class="row">
            <label class="remember">
              <input type="checkbox" id="remember" name="remember" />
              <span>Ghi nhớ đăng nhập</span>
            </label>
            <a href="Forget_password.php" class="link">Quên mật khẩu?</a>
          </div>

          <button type="submit" class="btn-primary">
            Đăng nhập <span aria-hidden="true">➜</span>
          </button>
        </form>

        <div class="login_divider"><span>Hoặc tiếp tục với</span></div>

        <div class="social">
          <button type="button" class="btn-social btn-google-login">
            <span class="g-icon"><img class="g-icon-img" src="assets/google.png" alt="Google"></span> Google
          </button>
          <button type="button" class="btn-social btn-facebook-login">
            <span class="fb-icon"><img class="fb-icon-img" src="assets/facebook.png" alt="Facebook"></span> Facebook
          </button>
        </div>

        <p class="login__bottom">
          Chưa có tài khoản? <a href="DangKy.php" class="login__bottom_link">Đăng ký ngay</a>
        </p>
      </div>
    </main>
  </body>
</html>