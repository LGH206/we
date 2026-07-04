<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Users.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new Users($db);
    }

    // Lấy danh sách (ĐÃ CẬP NHẬT: Hỗ trợ tìm kiếm và phân trang)
    public function listUsers($search = null, $role = 'all', $limit = 10, $offset = 0) {
        // Truyền thêm $limit và $offset sang cho hàm read() trong Model xử lý SQL
        return $this->userModel->read($search, $role, $limit, $offset);
    }

    // Đảo trạng thái: active <-> blocked
    public function toggleStatus($id, $currentStatus) {
        $newStatus = ($currentStatus == 'active') ? 'blocked' : 'active';
        return $this->userModel->updateStatus($id, $newStatus);
    }

    // Xóa user
    public function deleteUser($id) {
        return $this->userModel->delete($id);
    }

    // Lấy tổng số lượng người dùng (cho phân trang)
    public function getTotalUsers() {
        return $this->userModel->countAll();
    }

    public function processAddUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_add'])) {
            $data = [
                'fullname' => $_POST['fullname'],
                'email'    => $_POST['email'],
                'password' => $_POST['password'],
                'role'     => $_POST['role']
            ];
            // Gọi model để lưu vào DB
            $this->userModel->create($data['fullname'], $data['email'], $data['password'], $data['role']);
            
            // Load lại trang để thấy user mới
            header("Location: Admin_user.php");
            exit();
        }
    }

    // Hàm thêm người dùng mới với 4 tham số
    public function addNewUser($fullname, $email, $password, $role) {
        return $this->userModel->create($fullname, $email, $password, $role);
    }

    public function getBlockedUsers() {
        return $this->userModel->countBlocked();
    }
}
?>