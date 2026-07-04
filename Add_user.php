<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Controllers/UserController.php';

// Khởi tạo Database và Controller ở trên cùng để dùng chung
$database = new Database();
$db = $database->getConnection();
$userController = new UserController($db); // Khởi tạo ở đây thì mọi nơi đều thấy

// Xử lý khi nhấn Lưu
if (isset($_POST['submit_add'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Bây giờ $userController chắc chắn đã tồn tại!
    $userController->addNewUser($fullname, $email, $password, $role);
    
    header("Location: Admin_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng — Đời sống sức khoẻ</title>
    <link rel="stylesheet" href="CSS/admin.css"> </head>
<body style="background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <div class="table-card" style="width: 100%; max-width: 450px; padding: 30px;">
        <h2 style="margin-bottom: 20px; color: #333;">Thêm người dùng mới</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            <div class="form-group">
                <label>Họ và Tên</label>
                <input type="text" name="fullname" class="input-field" placeholder="Nhập họ tên" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input-field" placeholder="Nhập email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="input-field" placeholder="Mật khẩu bảo mật" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="user">Độc giả</option>
                    <option value="admin">Quản trị viên</option>
                </select>
            </div>

            <div style="margin-top: 10px; display: flex; gap: 10px;">
                <button type="submit" name="submit_add" class="btn-primary" style="flex: 1; padding: 12px;">Lưu thông tin</button>
                <a href="Admin_user.php" class="btn-secondary" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: #333;">Huỷ bỏ</a>
            </div>
        </form>
    </div>

</body>
</html>