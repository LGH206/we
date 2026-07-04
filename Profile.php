<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'suckhoe');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Không thể kết nối đến máy chủ.']));
}

// --- PHẦN XỬ LÝ API (BACKEND) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
      echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
      exit;
    }

    $userId = $_SESSION['user_id'];

    // Xử lý cập nhật thông tin
    if (isset($_POST['fullName'])) {
      $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ? WHERE id = ?");
      $stmt->execute([$_POST['fullName'], $_POST['phone'], $userId]);
      echo json_encode(['success' => true, 'message' => 'Đã lưu thông tin!']);
    }
    // Xử lý đổi mật khẩu
    elseif (isset($_POST['newPassword'])) {
      $hashed = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
      $stmt->execute([$hashed, $userId]);
      echo json_encode(['success' => true, 'message' => 'Đã đổi mật khẩu!']);
    }
    exit(); // Dừng mọi thứ, không chạy tiếp xuống phần HTML
}


include 'header.php';
?>

    <div class="profile__layout">
      <!-- Sidebar -->
      <aside class="profile__sidebar">
        <a href="#" class="profile__nav-item active">
          <span>Profile</span>
        </a>
      </aside>

      <!-- Main Content -->
      <main class="main">
        <!-- Profile Information -->
        <section class="card">
          <h2 class="card-title">Thông tin cá nhân</h2>
          <p class="card-desc">Cập nhật ảnh và thông tin chi tiết</p>

          <form class="profile-form" id="profileForm">
            <div class="form-row">
              <div class="avatar-col">
                <div class="avatar-wrap-user">
                  <img id="avatarPreview" src="" alt="Avatar" class="avatar-img" />
                  <button type="button" class="avatar-edit" id="avatarEdit" aria-label="Đổi ảnh đại diện">📷</button>
                  <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/gif" hidden />
                </div>
              </div>

              <div class="fields-col">
                <div class="field-row two-col">
                  <div class="field">
                    <label for="fullName">Họ và tên</label>
                    <input type="text" id="fullName"/>
                  </div>
                  <div class="field">
                    <label for="email">Địa chỉ email</label>
                    <input type="email" id="email" readonly /> 
                  </div>
                </div>
                <div class="field-row">
                  <div class="field">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </section>

        <!-- Password Update -->
        <section class="card">
          <h2 class="card-title">Thay đổi mật khẩu</h2>
          <p class="card-desc">Hãy đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để giữ an toàn.</p>

          <form class="password-form" id="passwordForm">
            <div class="field">
              <label for="newPassword">Nhập mật khẩu cũ</label>
              <div class="password-wrap">
                <input type="password" id="newPassword"/>
                <button type="button" class="toggle-pw" data-target="newPassword" aria-label="Hiện mật khẩu">
                  <span>👁️</span>
                </button>
              </div>
              <br>

              <label for="newPassword">Mật khẩu mới</label>
              <div class="password-wrap">
                <input type="password" id="newPassword"/>
                <button type="button" class="toggle-pw" data-target="newPassword" aria-label="Hiện mật khẩu">
                  <span>👁️</span>
                </button>
              </div>
              <div class="strength-bar">
                <div class="strength-fill" style="width: 0%"></div>
              </div>
              <p class="strength-text">Độ mạnh</p>
            </div>

            <div class="field">
              <label for="confirmPassword">Xác nhận mật khẩu mới</label>
              <div class="password-wrap">
                <input type="password" id="confirmPassword"/>
                <button type="button" class="toggle-pw" data-target="confirmPassword" aria-label="Hiện mật khẩu">
                  <span>👁️</span>
                </button>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-secondary">Cập nhật mật khẩu</button>
            </div>
          </form>
        </section>
      </main>
    </div>
    <script src="JavaScript/profile.js"></script>
    <!-- Footer -->
    <?php include 'footer.php'; ?>
  </body>
</html>
