<?php
// Gọi Model Category vào để xử lý nghiệp vụ dữ liệu
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Category.php';

class CategoryController {
    private $db;
    private $categoryModel;

    // Khởi tạo Controller kèm theo thực thể kết nối DB
    public function __construct($db) {
        $this->db = $db;
        $this->categoryModel = new Category($db);
    }

    // Lấy tổng số danh mục trên hệ thống để trả về giao diện hiển thị
    public function getTotalCategories() {
        return $this->categoryModel->getTotalCount();
    }

    // Lấy thông tin 1 danh mục cụ thể theo ID
    public function getCategoryById($id) {
        if (empty($id)) return false;
        return $this->categoryModel->getById($id);
    }

    // Thực thi xử lý hành động thêm danh mục dữ liệu mới
    public function addCategory($data) {
        if (empty($data['name'])) return false;
        return $this->categoryModel->create($data);
    }

    // Thực thi xử lý hành động cập nhật thông tin danh mục thay đổi
    public function updateCategory($id, $data) {
        if (empty($id) || empty($data['name'])) return false;
        return $this->categoryModel->update($id, $data);
    }

    // Thực thi xử lý hành động xóa danh mục dữ liệu
    public function deleteCategory($id) {
        if (empty($id)) return false;
        return $this->categoryModel->delete($id);
    }
}
?>