<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Post.php';

class PostController {
    private $postModel;

    public function __construct($db) {
        $this->postModel = new Post($db);
    }

    // Lấy danh sách bài viết
    public function listPosts($search = null, $category = 'all') {
        return $this->postModel->read($search, $category);
    }

    // Thêm bài viết mới (Bỏ nhận tham số $summary)
    public function addNewPost($title, $content, $category_id, $user_id) {
        return $this->postModel->create($title, $content, $category_id, $user_id);
    }

    // Cập nhật bài viết
    public function editPost($id, $title, $category_id, $content) {
        return $this->postModel->update($id, $title, $category_id, $content);
    }

    // Xóa bài viết
    public function deletePost($id) {
        return $this->postModel->delete($id);
    }

    // Lấy tổng số lượng bài viết để hiển thị thống kê
    public function getTotalPosts() {
        return $this->postModel->countAll();
    }
}
?>