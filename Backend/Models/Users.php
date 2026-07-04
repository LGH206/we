<?php
class Users {
    private $conn;
    private $table_name = "users";

    // Thuộc tính map với bảng CSDL
    public $id;
    public $fullname;
    public $email;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra xem Email đã tồn tại trong hệ thống chưa
    public function emailExists($email) {
        // 1. Loại bỏ khoảng trắng thừa ở đầu và cuối
        $email = trim($email);
        
        $query = "SELECT id, fullname, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        // 2. Debug: Nếu không tìm thấy, hãy thử log kết quả hoặc xem rowCount
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Debug: In ra email bạn đang dùng để truy vấn nếu nó không tìm thấy
        error_log("Email not found in DB: " . $email); 
        return false;
    }

    // Tạo tài khoản mới (Đăng ký)
    public function create($fullname, $email, $password, $role = 'user') {
        // Kiểm tra trùng email trước khi tạo
        if($this->emailExists($email)) {
            return "email_exists";
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET fullname = :fullname, email = :email, password = :password, role = :role";
        
        $stmt = $this->conn->prepare($query);

        // Mã hóa bảo mật mật khẩu bằng thuật toán BCRYPT trước khi lưu vào DB
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Bind dữ liệu phòng chống SQL Injection
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $role);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Hàm lấy danh sách user (Đmar bảo ĐÃ CẬP NHẬT: Thêm tham số $limit và $offset)
    public function read($search = null, $role = null, $limit = 10, $offset = 0) {
        $query = "SELECT * FROM users WHERE 1=1"; // 1=1 để dễ nối thêm điều kiện
        
        if ($search) {
            $query .= " AND (fullname LIKE :search OR email LIKE :search)";
        }
        if ($role && $role != 'all') {
            $query .= " AND role = :role";
        }
        
        // BẮT BUỘC: Thêm LIMIT và OFFSET vào câu SQL để phân chia trang dữ liệu
        $query .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        
        // Bind dữ liệu tìm kiếm và phân quyền
        if ($search) $stmt->bindValue(':search', "%$search%");
        if ($role && $role != 'all') $stmt->bindValue(':role', $role);
        
        // Bắt buộc bind kiểu INT (PDO::PARAM_INT) cho phân trang hoạt động chuẩn xác
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Khóa hoặc Mở khóa người dùng
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    // 3. Xóa người dùng
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // 4. Đếm tổng người dùng (cho phân trang)
    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name);
        return $stmt->fetchColumn();
    }
    
    // Lấy thông tin 1 người dùng
    public function getById($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin
    public function update($id, $fullname, $email, $role) {
        $query = "UPDATE users SET fullname = :fullname, email = :email, role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id, ':fullname' => $fullname, ':email' => $email, ':role' => $role]);
    }

    public function countByStatus($status) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE status = :status");
        $stmt->execute([':status' => $status]);
        return $stmt->fetchColumn();
    }
    
    // Đếm người dùng bị khóa (status = 'blocked')
    public function countBlocked() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name . " WHERE status = 'blocked'");
        return $stmt->fetchColumn();
    }

}
?>