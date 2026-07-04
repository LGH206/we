<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Config/db.php';

$db = (new Database())->getConnection();

// Lấy ID từ URL
$id = $_GET['id'] ?? null; //[cite: 36]
if (!$id) { header("Location: Admin_post.php"); exit(); } //[cite: 36]

// LẤY DỮ LIỆU BÀI VIẾT CŨ TRỰC TIẾP BẰNG PDO THUẦN
$stmtPost = $db->prepare("SELECT * FROM posts WHERE id = :id");
$stmtPost->execute([':id' => $id]);
$post = $stmtPost->fetch(PDO::FETCH_ASSOC);

if (!$post) { header("Location: Admin_post.php"); exit(); }

// Lấy danh mục để hiển thị tuyển chọn lại
$queryCategory = "SELECT id, name FROM categories ORDER BY id ASC";
$stmtCategory = $db->prepare($queryCategory);
$stmtCategory->execute();
$categories = $stmtCategory->fetchAll(PDO::FETCH_ASSOC);

// XỬ LÝ CẬP NHẬT TRỰC TIẾP VÀO DATABASE
if (isset($_POST['submit_update'])) {
    $title = $_POST['title']; //[cite: 36]
    $category_id = $_POST['category_id']; //[cite: 36]
    $content = $_POST['content']; //[cite: 36]

    try {
        // ĐÃ SỬA: Chuyển 'category = :category' thành 'category_id = :category_id'
        $sql = "UPDATE posts SET title = :title, category_id = :category_id, content = :content WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':title'       => $title,
            ':category_id' => $category_id,
            ':content'     => $content,
            ':id'          => $id
        ]);

        header("Location: Admin_post.php");
        exit();
    } catch (PDOException $e) {
        echo "❌ Lỗi cập nhật dữ liệu: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài viết — Đời sống sức khoẻ</title>
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body style="background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="table-card" style="width: 100%; max-width: 450px; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; color: #333;">Chỉnh sửa bài viết</h2>
        
        <form method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Tiêu đề -->
            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Tiêu đề bài viết</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required 
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
            </div>
            
            <!-- Danh mục -->
            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Danh mục</label>
                <select name="category_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: white;">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <!-- ĐÃ SỬA: So sánh trực tiếp mã số id với thuộc tính category_id trong bảng posts -->
                        <option value="<?php echo $cat['id']; ?>" <?php if(($post['category_id'] ?? '') == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nội dung chi tiết -->
            <div class="form-group">
                <label style="font-weight: 600; color: #555;">Nội dung chi tiết</label>
                <textarea name="content" rows="8" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit;"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <!-- Nút bấm điều hướng đồng bộ với cấu trúc mẫu tại image_fdd5ec.png -->
            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <button type="submit" name="submit_update" class="btn-primary" style="flex: 1; padding: 12px; background-color: #155736; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Lưu thông tin</button>
                <a href="Admin_post.php" class="btn-secondary" style="flex: 1; padding: 12px; text-align: center; text-decoration: none; color: #333; border: 1px solid #ddd; border-radius: 6px; background: #fff;">Huỷ bỏ</a>
            </div>
        </form>
    </div>
</body>
</html>