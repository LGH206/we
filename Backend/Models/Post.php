<?php
class Post {
    private $conn;
    private $table_name = "posts";

    // Cập nhật các thuộc tính ánh xạ đúng theo ảnh image_c6eb27.png
    public $id;
    public $category_id;
    public $user_id;
    public $title;
    public $content;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Thêm bài viết mới (Theo đúng cấu trúc database hiện tại)
    public function create($title, $content, $category_id, $user_id) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET title = :title, 
                      content = :content, 
                      category_id = :category_id, 
                      user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":user_id", $user_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 2. Lấy danh sách bài viết (Bỏ điều kiện lọc status)
    public function read($search = null, $category = 'all') {
        $query = "SELECT p.*, u.fullname as author_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE 1=1";
        
        if ($search) {
            $query .= " AND (p.title LIKE :search OR p.content LIKE :search)";
        }
        
        if ($category && $category != 'all') {
            $query .= " AND p.category_id = :category_id";
        }
        
        $query .= " ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        
        if ($search) {
            $stmt->bindValue(':search', "%$search%");
        }
        if ($category && $category != 'all') {
            $stmt->bindValue(':category_id', $category);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy chi tiết 1 bài viết
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Cập nhật bài viết (Bỏ summary)
    public function update($id, $title, $category_id, $content) {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      category_id = :category_id, 
                      content = :content 
                  WHERE id = :id";
                  
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id, 
            ':title' => $title, 
            ':category_id' => $category_id, 
            ':content' => $content
        ]);
    }

    // 5. Xóa bài viết
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // 6. Đếm tổng số lượng bài viết
    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name);
        return $stmt->fetchColumn();
    }
}
?>