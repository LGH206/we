<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Visit.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Post.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Comment.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Users.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Category.php';

class DashboardController {
    private $db;
    private $visitModel;
    private $postModel;
    private $commentModel;
    private $userModel;
    private $categoryModel;

    public function __construct($db) {
        $this->db = $db;
        $this->visitModel = new Visit($db);
        $this->postModel = new Post($db);
        $this->commentModel = new Comment($db);
        $this->userModel = new Users($db);
        $this->categoryModel = new Category($db);
    }

    public function getOverview() {
        return [
            'total_users'     => $this->userModel->countAll(),
            'total_posts'     => $this->postModel->countAll(),
            'total_comments'  => $this->commentModel->countAll(),
            'total_categories'=> $this->categoryModel->getTotalCount()
        ];
    }

    /**
     * HÀM MỚI TỐI ƯU: Nhận yêu cầu từ JS và kết nối CSDL lấy dữ liệu biểu đồ
     * Trả về định dạng sạch: { labels: [], posts: [], users: [], comments: [] }
     */
    public function getTrafficChartData($type = 'monthly', $year = null) {
        if (!$year) $year = date('Y');

        if ($type === 'weekly') {
            return $this->getWeeklyChartData();
        }

        return $this->getMonthlyChartData($year);
    }

    /**
     * Lấy dữ liệu 12 tháng theo năm (Monthly)
     */
    private function getMonthlyChartData($year) {
        $labels = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];
        
        // Khởi tạo mảng giá trị mặc định 0 cho 12 tháng tránh lệch biểu đồ
        $posts = array_fill(0, 12, 0);
        $users = array_fill(0, 12, 0);
        $comments = array_fill(0, 12, 0);

        try {
            // 1. Lấy dữ liệu bài viết theo từng tháng từ database
            $queryPosts = "SELECT MONTH(created_at) as month, COUNT(*) as total 
                           FROM posts 
                           WHERE YEAR(created_at) = :year 
                           GROUP BY month";
            $stmt = $this->db->prepare($queryPosts);
            $stmt->execute([':year' => $year]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $posts[(int)$row['month'] - 1] = (int)$row['total'];
            }

            // 2. Lấy dữ liệu người dùng đăng ký theo từng tháng từ database
            $queryUsers = "SELECT MONTH(created_at) as month, COUNT(*) as total 
                           FROM users 
                           WHERE YEAR(created_at) = :year 
                           GROUP BY month";
            $stmt = $this->db->prepare($queryUsers);
            $stmt->execute([':year' => $year]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $users[(int)$row['month'] - 1] = (int)$row['total'];
            }

            // 3. Lấy dữ liệu bình luận được tạo theo từng tháng từ database
            $queryComments = "SELECT MONTH(created_at) as month, COUNT(*) as total 
                              FROM comments 
                              WHERE YEAR(created_at) = :year 
                              GROUP BY month";
            $stmt = $this->db->prepare($queryComments);
            $stmt->execute([':year' => $year]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $comments[(int)$row['month'] - 1] = (int)$row['total'];
            }
        } catch (PDOException $e) {
            // Ghi log lỗi nếu cần thiết
        }

        return [
            'labels'   => $labels,
            'posts'    => $posts,
            'users'    => $users,
            'comments' => $comments
        ];
    }

    /**
     * Lấy dữ liệu 8 tuần gần nhất (Weekly)
     */
    private function getWeeklyChartData() {
        $labels = [];
        $weeksMap = [];

        // Tạo cấu trúc trục thời gian cho 8 tuần gần nhất theo chuẩn ISO 8601
        for ($i = 7; $i >= 0; $i--) {
            $time = strtotime("-$i week Monday");
            $weekNum = date('W', $time);
            $isoYear = date('o', $time);
            
            $key = $isoYear . $weekNum;
            $weeksMap[$key] = 7 - $i;
            $labels[] = "Tuần " . $weekNum;
        }

        $posts = array_fill(0, 8, 0);
        $users = array_fill(0, 8, 0);
        $comments = array_fill(0, 8, 0);

        // Mốc thời gian bắt đầu của tuần thứ 8 về trước
        $startDate = date('Y-m-d 00:00:00', strtotime("-7 week Monday"));

        try {
            // 1. Thống kê bài viết (Gộp nhóm bằng YEARWEEK mode 3 của MySQL chạy duy nhất 1 query)
            $queryPosts = "SELECT YEARWEEK(created_at, 3) as yw, COUNT(*) as total 
                           FROM posts 
                           WHERE created_at >= :start 
                           GROUP BY yw";
            $stmt = $this->db->prepare($queryPosts);
            $stmt->execute([':start' => $startDate]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $yw = $row['yw'];
                if (isset($weeksMap[$yw])) {
                    $posts[$weeksMap[$yw]] = (int)$row['total'];
                }
            }

            // 2. Thống kê người dùng
            $queryUsers = "SELECT YEARWEEK(created_at, 3) as yw, COUNT(*) as total 
                           FROM users 
                           WHERE created_at >= :start 
                           GROUP BY yw";
            $stmt = $this->db->prepare($queryUsers);
            $stmt->execute([':start' => $startDate]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $yw = $row['yw'];
                if (isset($weeksMap[$yw])) {
                    $users[$weeksMap[$yw]] = (int)$row['total'];
                }
            }

            // 3. Thống kê bình luận
            $queryComments = "SELECT YEARWEEK(created_at, 3) as yw, COUNT(*) as total 
                              FROM comments 
                              WHERE created_at >= :start 
                              GROUP BY yw";
            $stmt = $this->db->prepare($queryComments);
            $stmt->execute([':start' => $startDate]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $yw = $row['yw'];
                if (isset($weeksMap[$yw])) {
                    $comments[$weeksMap[$yw]] = (int)$row['total'];
                }
            }
        } catch (PDOException $e) {
            // Ghi log lỗi nếu cần thiết
        }

        return [
            'labels'   => $labels,
            'posts'    => $posts,
            'users'    => $users,
            'comments' => $comments
        ];
    }

    public function getTopViewedPosts($limit = 5) {
        return $this->visitModel->getTopViewedPosts($limit);
    }

    public function getTopCategories($limit = 5) {
        $query = "SELECT c.id, c.name, COUNT(p.id) as post_count 
                  FROM categories c
                  LEFT JOIN posts p ON c.id = p.category_id
                  GROUP BY c.id
                  ORDER BY post_count DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopAuthors($limit = 5) {
        $query = "SELECT u.id, u.fullname, COUNT(p.id) as post_count 
                  FROM users u
                  JOIN posts p ON u.id = p.user_id
                  GROUP BY u.id
                  ORDER BY post_count DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>