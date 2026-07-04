/**
 * admin_comment.js
 * Quản lý bình luận – gọi API backend, phân trang, duyệt, xóa, AI toggle
 */

document.addEventListener('DOMContentLoaded', function() {
    const API_BASE = '/web/Backend/routes/api.php';
    let currentPage = 1;
    let currentStatus = 'all';
    const LIMIT = 10;

    // ========== LOAD COMMENTS ==========
    function loadComments(page = 1, status = 'all') {
        currentPage = page;
        currentStatus = status;

        fetch(`${API_BASE}/api/comments?page=${page}&limit=${LIMIT}&status=${status}`)
            .then(res => res.json())
            .then(data => {
                renderTable(data.data || []);
                renderPagination(data.total || 0, page);
                updateStats(data.total || 0);
            })
            .catch(err => {
                console.error('Lỗi tải bình luận:', err);
                document.getElementById('comment-tbody').innerHTML =
                    `<tr><td colspan="6" style="text-align:center;color:red;">Lỗi tải dữ liệu</td></tr>`;
            });
    }

    // ========== RENDER TABLE ==========
    function renderTable(comments) {
        const tbody = document.getElementById('comment-tbody');
        if (!comments || comments.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Không có bình luận</td></tr>`;
            return;
        }

        tbody.innerHTML = comments.map(c => {
            const statusText = c.status === 'pending' ? 'Chờ duyệt' :
                               c.status === 'approved' ? 'Đã duyệt' : 'Từ chối';
            const statusClass = c.status;
            return `
                <tr data-id="${c.id}">
                    <td>#${c.id}</td>
                    <td>${c.user_name || 'Người dùng'}</td>
                    <td>${c.content}</td>
                    <td>${c.post_title || 'Bài viết #' + c.post_id}</td>
                    <td><span class="chip ${statusClass}">${statusText}</span></td>
                    <td>
                        ${c.status === 'pending' ? `<button class="btn-approve" data-id="${c.id}">Duyệt</button>` : ''}
                        <button class="btn-delete" data-id="${c.id}">Xóa</button>
                    </td>
                </tr>
            `;
        }).join('');

        // Sự kiện duyệt
        document.querySelectorAll('.btn-approve').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (!confirm('Duyệt bình luận #' + id + '?')) return;
                fetch(`${API_BASE}/api/comments/${id}/approve`, { method: 'POST' })
                    .then(res => res.json())
                    .then(() => loadComments(currentPage, currentStatus))
                    .catch(err => alert('Lỗi duyệt: ' + err));
            });
        });

        // Sự kiện xóa
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (!confirm('Xóa bình luận #' + id + '?')) return;
                fetch(`${API_BASE}/api/comments/${id}`, { method: 'DELETE' })
                    .then(res => res.json())
                    .then(() => loadComments(currentPage, currentStatus))
                    .catch(err => alert('Lỗi xóa: ' + err));
            });
        });
    }

    // ========== PAGINATION ==========
    function renderPagination(total, page) {
        const totalPages = Math.ceil(total / LIMIT);
        const container = document.getElementById('pagination-pages');
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        let html = '';
        for (let i = 1; i <= totalPages; i++) {
            html += `<button class="page-btn ${i === page ? 'is-active' : ''}" data-page="${i}">${i}</button>`;
        }
        container.innerHTML = html;
        container.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                loadComments(parseInt(this.dataset.page), currentStatus);
            });
        });
        document.getElementById('pagination-info').textContent =
            `Hiển thị trang ${page}/${totalPages} (${total} bình luận)`;
    }

    // ========== UPDATE STATS ==========
    function updateStats(total) {
        document.getElementById('totalComments').textContent = total;
        // Lấy số lượng bình luận bị từ chối (rejected)
        fetch(`${API_BASE}/api/comments?status=rejected&limit=1`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('violationCount').textContent = data.total || 0;
            })
            .catch(() => {});
    }

    // ========== AI TOGGLE ==========
    const aiSwitch = document.getElementById('ai-switch');
    const aiStatus = document.getElementById('ai-status');
    if (aiSwitch) {
        aiSwitch.addEventListener('change', function() {
            const value = this.checked ? '1' : '0';
            fetch(`${API_BASE}/api/settings`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ key: 'ai_comment_moderation', value: value })
            })
            .then(res => res.json())
            .then(() => {
                aiStatus.innerHTML = `<span class="dot"></span> Đại lý AI ${this.checked ? 'đang chạy' : 'đã tắt'}`;
                alert('Cập nhật trạng thái AI thành công');
            })
            .catch(err => alert('Lỗi cập nhật AI: ' + err));
        });
    }

    // ========== APPROVE ALL ==========
    document.getElementById('approveAllBtn')?.addEventListener('click', function() {
        if (!confirm('Phê duyệt tất cả bình luận đang chờ?')) return;
        fetch(`${API_BASE}/api/comments/approve-all`, { method: 'POST' })
            .then(res => res.json())
            .then(() => loadComments(currentPage, currentStatus))
            .catch(err => alert('Lỗi phê duyệt hàng loạt: ' + err));
    });

    // ========== FILTER ==========
    document.getElementById('filterBtn')?.addEventListener('click', function() {
        const status = prompt('Nhập trạng thái (all, pending, approved, rejected):', currentStatus);
        if (status !== null && ['all','pending','approved','rejected'].includes(status)) {
            loadComments(1, status);
        } else if (status !== null) {
            alert('Trạng thái không hợp lệ');
        }
    });

    // ========== SEARCH (đơn giản) ==========
    document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const keyword = this.value.trim();
            if (keyword) {
                // Tìm kiếm: gọi API với tham số search (cần bổ sung ở backend)
                // Vì backend chưa hỗ trợ search comment, ta chỉ filter frontend tạm thời
                // Hoặc có thể gọi lại API với tham số search
                alert('Chức năng tìm kiếm đang phát triển');
            } else {
                loadComments(1, currentStatus);
            }
        }
    });

    // ========== INIT ==========
    loadComments(1, 'all');
});