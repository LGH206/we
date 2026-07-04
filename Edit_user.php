<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Users.php';

$db = (new Database())->getConnection();
$userModel = new Users($db);

// Lấy ID từ URL
$id = $_GET['id'] ?? null;
if (!$id) { header("Location: Admin_user.php"); exit(); }

$user = $userModel->getById($id);

// Xử lý cập nhật
if (isset($_POST['submit_update'])) {
    $userModel->update($id, $_POST['fullname'], $_POST['email'], $_POST['role']);
    header("Location: Admin_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin</title>
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body style="background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <div class="table-card" style="width: 100%; max-width: 450px; padding: 30px;">
        <h2 style="margin-bottom: 20px; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px;">Chỉnh sửa thông tin</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Họ và Tên</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required 
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required 
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
            </div>

            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Vai trò</label>
                <select name="role" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Quản trị viên</option>
                    <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>Độc giả</option>
                </select>
            </div>

            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <button type="submit" name="submit_update" class="btn-primary" style="flex: 2; padding: 12px; cursor: pointer;">Cập nhật</button>
                <a href="Admin_user.php" class="btn-secondary" style="flex: 1; padding: 12px; text-align: center; text-decoration: none; color: #333; border: 1px solid #ddd; border-radius: 6px;">Huỷ</a>
            </div>
        </form>
    </div>
</body>
</html>