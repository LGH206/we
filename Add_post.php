<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';

$database = new Database();
$db = $database->getConnection();

// Tự động khởi tạo danh mục nếu trống để tránh lỗi dữ liệu
$checkCats = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
if ($checkCats == 0) {
    $sampleCategories = array('Tin tức y tế', 'Lối sống khỏe', 'Bệnh lý', 'Sơ cứu', 'Hệ tuần hoàn', 'Hệ hô hấp', 'Hệ tiêu hoá', 'Hệ cơ xương khớp', 'Hệ thần kinh', 'Hệ nội tiết và chuyển hoá', 'Hệ thận và tiết niệu', 'Hệ sinh dục và sinh sản', 'Hệ cơ quan giác quan', 'Bệnh ngoài da', 'Hệ miễn dịch');
    $insertStmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
    foreach ($sampleCategories as $catName) {
        $insertStmt->execute(array('name' => $catName));
    }
}

// Lấy danh sách danh mục để hiển thị trong thẻ <select>
$queryCategory = "SELECT id, name FROM categories ORDER BY id ASC";
$stmtCategory = $db->prepare($queryCategory);
$stmtCategory->execute();
$categories = $stmtCategory->fetchAll(PDO::FETCH_ASSOC);

// XỬ LÝ LƯU TRỰC TIẾP VÀO DATABASE
if (isset($_POST['submit_add'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id']; // Lấy trực tiếp ID số từ form[cite: 36]
    
    session_start();
    $user_id = $_SESSION['user_id'] ?? 1; //[cite: 36]

    try {
        // ĐÃ SỬA: Thay cột 'category' thành 'category_id' để khớp với cấu trúc Database của bạn
        $sql = "INSERT INTO posts (user_id, title, content, category_id, created_at) 
                VALUES (:user_id, :title, :content, :category_id, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':user_id'     => $user_id,
            ':title'       => $title,
            ':content'     => $content,
            ':category_id' => $category_id
        ]);
        
        header("Location: Admin_post.php");
        exit();
    } catch (PDOException $e) {
        echo "❌ Lỗi lưu dữ liệu: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm bài viết — Đời sống sức khoẻ</title>
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body style="background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <div class="table-card" style="width: 100%; max-width: 450px; padding: 30px;">
        <h2 style="margin-bottom: 20px; color: #333;">Thêm bài viết mới</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Tiêu đề -->
            <div class="form-group">
                <label>Tiêu đề bài viết</label>
                <input type="text" name="title" class="input-field" placeholder="Nhập tiêu đề bài viết" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <!-- Danh mục -->
            <div class="form-group">
                <label>Danh mục</label>
                <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fff;">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nội dung chi tiết -->
            <div class="form-group">
                <label>Nội dung chi tiết</label>
                <textarea name="content" rows="8" placeholder="Viết nội dung bài viết vào đây..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit;"></textarea>
            </div>

            <!-- Nút bấm điều hướng đồng bộ với image_fdd5ec.png -->
            <div style="margin-top: 10px; display: flex; gap: 10px;">
                <button type="submit" name="submit_add" class="btn-primary" style="flex: 1; padding: 12px; background-color: #155736; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Lưu thông tin</button>
                <a href="Admin_post.php" class="btn-secondary" style="flex: 1; padding: 12px; text-align: center; border: 1px solid #ddd; border-radius: 6px; text-decoration: none; color: #333; background: #fff;">Huỷ bỏ</a>
            </div>
        </form>
    </div>

</body>
</html>