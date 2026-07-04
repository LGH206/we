<?php
class Visit {
    private $conn;
    private $table_name = "visits";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tăng lượt xem cho bài viết
    public function incrementView($post_id) {
        $today = date('Y-m-d');
        $query = "INSERT INTO " . $this->table_name . " (post_id, view_count, date) 
                  VALUES (:post_id, 1, :date) 
                  ON DUPLICATE KEY UPDATE view_count = view_count + 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', $post_id);
        $stmt->bindValue(':date', $today);
        return $stmt->execute();
    }

    // Lấy tổng lượt xem của 1 bài viết
    public function getTotalViewsByPost($post_id) {
        $query = "SELECT SUM(view_count) as total FROM " . $this->table_name . " WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':post_id', $post_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Top bài viết nhiều lượt xem nhất
    public function getTopViewedPosts($limit = 5) {
        $query = "SELECT p.id, p.title, SUM(v.view_count) as total_views 
                  FROM " . $this->table_name . " v
                  JOIN posts p ON v.post_id = p.id
                  GROUP BY v.post_id
                  ORDER BY total_views DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lượt xem theo tháng (cho biểu đồ)
    public function getViewsByMonth($year = null) {
        if (!$year) $year = date('Y');
        $query = "SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(view_count) as total 
                  FROM " . $this->table_name . " 
                  WHERE YEAR(date) = :year
                  GROUP BY month
                  ORDER BY month";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':year', $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>