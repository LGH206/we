<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'suckhoe');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Không thể kết nối đến máy chủ.']));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
    $conn = new mysqli('localhost', 'root', '', 'suckhoe');

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối CSDL.']);
        exit; // DỪNG LẠI: Không chạy HTML bên dưới
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (empty($fullname) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đủ thông tin.']);
        exit; // DỪNG LẠI
    }

    if ($password !== $confirm) {
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu xác nhận không khớp.']);
        exit; // DỪNG LẠI
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email đã tồn tại.']);
        exit; // DỪNG LẠI
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống.']);
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đời sống sức khoẻ</title>
    <meta name="description" content="Tạo tài khoản mới để tham gia cộng đồng Sống Khỏe." />
    <link rel="stylesheet" href="CSS/main.css" />
    <script src="JavaScript/register.js" defer></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet" />
  </head>
  <body>
    <main class="regis-page">
      <div class="regis-card">
        <div class="regis-header">
            <div class="logo">
                <img class="logo_img" src="assets/logo.png" alt="Đăng Ký">
            </div>
          <h1 class="title">Đăng ký</h1>
          <p class="subtitle">Tạo tài khoản để bắt đầu</p>
        </div>

        <form class="form" id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
          <div class="field">
            <label for="fullname">Họ và tên</label>
            <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A" />
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="nhap@email.com" />
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

          <div class="field">
            <label for="confirm">Xác nhận mật khẩu</label>
            <div class="password-wrap">
              <input type="password" id="confirm" name="confirm" placeholder="••••••••" />
              <button type="button" class="toggle-pw" id="toggleConfirm" aria-label="Hiện mật khẩu">
                <span id="eyeIcon2">👁️</span>
              </button>
            </div>
          </div>

          <div class="row">
            <label class="remember">
              <input type="checkbox" id="agree" name="agree" />
              <span>Tôi đồng ý với điều khoản sử dụng</span>
            </label>
          </div>

          <button type="submit" class="btn-primary">
            Đăng ký <span aria-hidden="true">➜</span>
          </button>
        </form>

        <div class="regis-divider"><span>Hoặc tiếp tục với</span></div>

        <div class="social">
          <button type="button" class="btn-social btn-google-regis">
            <span class="g-icon"><img class="g-icon-img" src="assets/google.png" alt="Google"></span> Google
          </button>
          <button type="button" class="btn-social btn-facebook-regis">
            <span class="fb-icon"><img class="fb-icon-img" src="assets/facebook.png" alt="Facebook"></span> Facebook
          </button>
        </div>

        <p class="regis__bottom">
          Đã có tài khoản? <a href="DangNhap.php" class="regis__bottom_link">Đăng nhập</a>
        </p>
      </div>
    </main>
  </body>
</html>