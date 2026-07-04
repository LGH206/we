<?php
session_start();

// 1. Khởi tạo kết nối bằng PDO (vì bên dưới bạn đang dùng biến $pdo)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=suckhoe;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Chỉ trả về JSON lỗi nếu đây là yêu cầu AJAX/API gửi lên
    if (isset($_POST['action'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Không thể kết nối đến máy chủ.']);
        exit;
    } else {
        // Nếu load trang bình thường, hiển thị thông báo lỗi thân thiện dưới dạng HTML
        die("Lỗi kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
    }
}

// 2. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['action'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
        exit;
    } else {
        // Nếu truy cập trang trực tiếp, chuyển hướng về trang login
        header('Location: login.php');
        exit;
    }
}

$userId = $_SESSION['user_id'];

// 3. Xử lý cập nhật thông tin (Chỉ trả về JSON khi có Action)
if (isset($_POST['action']) && $_POST['action'] === 'update_info') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ? WHERE id = ?");
        $stmt->execute([$_POST['fullname'], $_POST['phone'], $userId]);
        echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin thành công!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
    }
    exit;
}

// 4. Xử lý cập nhật mật khẩu (Chỉ trả về JSON khi có Action)
if (isset($_POST['action']) && $_POST['action'] === 'update_password') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        // 1. Kiểm tra mật khẩu cũ (tùy chọn nhưng nên có)
        // 2. Hash mật khẩu mới
        $newHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newHash, $userId]);
        echo json_encode(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
    }
    exit();
}


$page_title = "Profile · Quản trị viên";
include 'admin_header.php'; 
?>

<main class="content">
  <section class="card">
    <header class="card-header">
      <h2>Thông tin cá nhân</h2>
      <p class="caption muted">Cập nhật ảnh và thông tin cá nhân chi tiết tại đây</p>
    </header>
    <div class="profile-row">
      <div class="avatar-block">
        <div class="change_avatar">
          <i class="icon-user" id="avatarPreview"></i>
          <button class="avatar-edit" title="Đổi ảnh" id="saveAvatar">✎</button>
          <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/gif" hidden />
        </div>
      </div>
      <div class="grid-2">
        <label class="field">
          <span>Họ và tên</span>
          <input type="text" />
        </label>
        <label class="field">
          <span>Địa chỉ email</span>
          <input type="email"/>
        </label>
        <label class="field span-2">
          <span>Số điện thoại</span>
          <input type="tel"/>
        </label>
      </div>
    </div>
    <div class="card-actions">
      <button class="btn btn-primary" id="saveProfile">Lưu thay đổi</button>
    </div>
  </section>

  <section class="card">
    <header class="card-header">
      <h2>Cập nhật mật khẩu</h2>
      <p class="caption muted">Hãy đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để giữ an toàn</p>
    </header>
    <div class="form-stack">
      <label class="field">
        <span>Mật khẩu cũ</span>
        <div class="input-wrap">
          <input type="password" data-pwd />
          <button class="toggle-pwd" type="button"><i class="icon-eye"></i></button>
        </div>
      </label>

      <label class="field">
        <span>Mật khẩu mới</span>
        <div class="input-wrap">
          <input type="password" id="newPwd" data-pwd />
          <button class="toggle-pwd" type="button"><i class="icon-eye"></i></button>
        </div>
        <div class="strength">
          <div class="strength-bar"><div id="strengthFill"></div></div>
          <p class="caption" id="strengthLabel">Độ mạnh</p>
        </div>
      </label>

      <label class="field">
        <span>Nhập lại mật khẩu mới</span>
        <div class="input-wrap">
          <input type="password" data-pwd />
          <button class="toggle-pwd" type="button"><i class="icon-eye"></i></button>
        </div>
      </label>
    </div>
    <div class="card-actions">
      <button class="btn btn-dark" id="updatePwd">Update Password</button>
    </div>
  </section>
</main>
<script src="JavaScript/Admin_profile.js"></script>
<?php include 'admin_footer.php'; ?>