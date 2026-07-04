<?php
require_once __DIR__ . '/../Config/db.php';
require_once __DIR__ . '/../Config/jwt.php';
require_once __DIR__ . '/../Models/Users.php';

class AuthController {
    private $db;
    private $userModel;
    private $jwtHandler;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new Users($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    // Xử lý Đăng ký
    public function register($data) {
        // Kiểm tra xem dữ liệu Front-end gửi lên có đủ không
        if (empty($data->fullname) || empty($data->email) || empty($data->password)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Vui lòng nhập đầy đủ thông tin bắt buộc!"]);
            return;
        }

        $result = $this->userModel->create($data->fullname, $data->email, $data->password);

        if ($result === true) {
            http_response_code(201); // Created
            echo json_encode(["message" => "Đăng ký tài khoản thành công!"]);
        } elseif ($result === "email_exists") {
            http_response_code(400); 
            echo json_encode(["message" => "Email này đã được đăng ký sử dụng trong hệ thống!"]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Đã có lỗi hệ thống xảy ra, vui lòng thử lại sau."]);
        }
    }

    // Xử lý Đăng nhập
    public function login($data) {
        if (empty($data->email) || empty($data->password)) {
            http_response_code(400);
            echo json_encode(["message" => "Vui lòng nhập cả email và mật khẩu!"]);
            return;
        }

        $user_record = $this->userModel->emailExists($data->email);

        // Xác thực mật khẩu đã băm (password_verify)
        if ($user_record && password_verify($data->password, $user_record['password'])) {
            
            // Cấu hình payload của JWT Token chứa thông tin phân quyền
            $payload = [
                "id" => $user_record['id'],
                "fullname" => $user_record['fullname'],
                "role" => $user_record['role'], // Lưu cột role: admin/user/editor
                "iat" => time(),                 // Thời gian phát hành token
                "exp" => time() + (60 * 60 * 24) // Thời gian hết hạn (Hạn dùng 1 ngày)
            ];

            $token = $this->jwtHandler->encode($payload);

            http_response_code(200); // OK
            echo json_encode([
                "message" => "Đăng nhập thành công!",
                "token" => $token,
                "user" => [
                    "fullname" => $user_record['fullname'],
                    "role" => $user_record['role']
                ]
            ]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Tài khoản email hoặc mật khẩu không chính xác!"]);
        }
    }

    // Thêm vào class AuthController
    public function checkEmail($data) {
        if (empty($data->email)) {
            http_response_code(400);
            echo json_encode(["message" => "Vui lòng nhập email"]);
            return;
        }

        $user = $this->userModel->emailExists($data->email);
        
        if ($user) {
            http_response_code(200);
            echo json_encode(["status" => "exists", "message" => "Email hợp lệ"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Không tìm thấy email"]);
        }
    }

    public function resetPasswordDirect($data) {
        // 1. Kiểm tra mật khẩu cũ
        $user = $this->userModel->emailExists($data->email);
        if ($user && password_verify($data->old_password, $user['password'])) {
            // 2. Kiểm tra mật khẩu mới trùng khớp
            if ($data->new_password === $data->confirm_password) {
                $this->userModel->updatePasswordByEmail($data->email, $data->new_password);
                echo json_encode(["message" => "Đổi mật khẩu thành công!"]);
            } else {
                echo json_encode(["message" => "Mật khẩu mới không khớp."]);
            }
        } else {
            echo json_encode(["message" => "Mật khẩu cũ không chính xác."]);
        }
    }
}
?>