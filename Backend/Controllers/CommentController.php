<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/web/Backend/Models/Comment.php';

class CommentController {
    private $commentModel;

    public function __construct($db) {
        $this->commentModel = new Comment($db);
    }

    // =============================================
    // PHẦN 1: ĐỌC / TÌM KIẾM BÌNH LUẬN
    // =============================================

    /**
     * Lấy danh sách bình luận (có lọc theo trạng thái, phân trang)
     * @param string|null $status  null = tất cả | 'pending' | 'approved' | 'rejected' | 'spam'
     * @param int         $limit   Số bình luận mỗi trang
     * @param int         $offset  Vị trí bắt đầu lấy
     */
    public function listComments($status = null, $limit = 10, $offset = 0) {
        return $this->commentModel->read($status, $limit, $offset);
    }

    /**
     * Tìm kiếm bình luận theo từ khóa (trong nội dung)
     * @param string      $keyword Từ khóa tìm kiếm
     * @param string|null $status  Lọc thêm theo trạng thái nếu có
     * @param int         $limit
     * @param int         $offset
     */
    public function searchComments($keyword, $status = null, $limit = 10, $offset = 0) {
        return $this->commentModel->search($keyword, $status, $limit, $offset);
    }

    /**
     * Đếm số bình luận theo từ khóa tìm kiếm
     */
    public function countSearch($keyword, $status = null) {
        return $this->commentModel->countSearch($keyword, $status);
    }

    // =============================================
    // PHẦN 2: THỐNG KÊ – DÀNH CHO KPI CARDS
    // =============================================

    /**
     * Đếm bình luận theo trạng thái
     * @param string|null $status null = đếm tất cả
     */
    public function countComments($status = null) {
        return $this->commentModel->count($status);
    }

    /**
     * Lấy toàn bộ số liệu thống kê cho trang quản lý (KPI cards)
     * Trả về: total, pending, approved, rejected, spam, auto_processed_24h
     */
    public function getStats() {
        return [
            'total'               => $this->commentModel->countAll(),
            'pending'             => $this->commentModel->count('pending'),
            'approved'            => $this->commentModel->count('approved'),
            'rejected'            => $this->commentModel->count('rejected'),
            'spam'                => $this->commentModel->count('spam'),
            'auto_processed_24h'  => $this->commentModel->countAutoProcessed24h(),
        ];
    }

    // =============================================
    // PHẦN 3: TẠO MỚI BÌNH LUẬN
    // =============================================

    /**
     * Tạo bình luận mới từ người dùng
     */
    public function createComment($post_id, $user_id, $content, $status = 'pending') {
        return $this->commentModel->create($post_id, $user_id, $content, $status);
    }

    // =============================================
    // PHẦN 4: KIỂM DUYỆT TỪNG BÌNH LUẬN
    // =============================================

    /**
     * Duyệt (approve) một bình luận
     */
    public function approveComment($id) {
        return $this->commentModel->updateStatus($id, 'approved');
    }

    /**
     * Từ chối (reject) một bình luận
     */
    public function rejectComment($id) {
        return $this->commentModel->updateStatus($id, 'rejected');
    }

    /**
     * Đánh dấu bình luận là spam
     */
    public function markAsSpam($id) {
        return $this->commentModel->updateStatus($id, 'spam');
    }

    /**
     * Xóa vĩnh viễn một bình luận
     */
    public function deleteComment($id) {
        return $this->commentModel->delete($id);
    }

    // =============================================
    // PHẦN 5: THAO TÁC HÀNG LOẠT (BULK ACTIONS)
    // =============================================

    /**
     * Phê duyệt toàn bộ bình luận đang chờ duyệt (pending)
     */
    public function approveAllPending() {
        return $this->commentModel->approveAllPending();
    }

    /**
     * Xóa hàng loạt các bình luận theo danh sách ID
     * @param array $ids Mảng các ID cần xóa, vd: [3, 7, 12]
     */
    public function bulkDelete(array $ids) {
        if (empty($ids)) return false;
        return $this->commentModel->deleteMultiple($ids);
    }

    /**
     * Đánh dấu hàng loạt bình luận là spam
     * @param array $ids Mảng các ID cần đánh spam
     */
    public function bulkMarkSpam(array $ids) {
        if (empty($ids)) return false;
        return $this->commentModel->updateStatusMultiple($ids, 'spam');
    }

    /**
     * Xóa toàn bộ bình luận đang bị gắn cờ spam
     */
    public function clearAllSpam() {
        return $this->commentModel->deleteByStatus('spam');
    }
}
?>