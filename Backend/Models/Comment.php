<?php
class Comment {
    private $conn;
    private $table_name = "comments";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách bình luận có phân trang, lọc theo status
    public function read($status = null, $limit = 10, $offset = 0) {
        $query = "SELECT c.*, u.fullname as user_name, p.title as post_title 
                  FROM " . $this->table_name . " c
                  LEFT JOIN users u ON c.user_id = u.id
                  LEFT JOIN posts p ON c.post_id = p.id
                  WHERE 1=1";
        if ($status && $status != 'all') {
            $query .= " AND c.status = :status";
        }
        $query .= " ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        if ($status && $status != 'all') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số bình luận theo status
    public function count($status = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        if ($status && $status != 'all') {
            $query .= " WHERE status = :status";
        }
        $stmt = $this->conn->prepare($query);
        if ($status && $status != 'all') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Tạo bình luận mới
    public function create($post_id, $user_id, $content, $status = 'pending') {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET post_id = :post_id, user_id = :user_id, content = :content, status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', $post_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':content', htmlspecialchars(strip_tags($content)));
        $stmt->bindValue(':status', $status);
        return $stmt->execute();
    }

    // Cập nhật trạng thái
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Xóa bình luận
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Đếm tổng số bình luận (không lọc)
    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name);
        return $stmt->fetchColumn();
    }

    // Duyệt tất cả pending
    public function approveAllPending() {
        $query = "UPDATE " . $this->table_name . " SET status = 'approved' WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?>