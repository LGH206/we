<?php
class Category {
    private $conn;
    private $table_name = "categories";

    // Các thuộc tính thực tế theo database
    public $id;
    public $name;
    public $created_at;

    // Hàm khởi tạo kết nối cơ sở dữ liệu
    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tổng số lượng danh mục hệ thống (Phục vụ thẻ Stats)
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Lấy thông tin một danh mục cụ thể theo ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới một danh mục (Chỉ truyền name)
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch và ràng buộc dữ liệu đầu vào
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($data['name'])));
        
        return $stmt->execute();
    }

    // Cập nhật thông tin tên danh mục theo ID
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch và ràng buộc dữ liệu đầu vào
        $stmt->bindValue(':name', htmlspecialchars(strip_tags($data['name'])));
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Xóa một danh mục theo mã ID
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>