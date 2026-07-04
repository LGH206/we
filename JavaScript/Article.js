/* =========================================================================
           CẤU HÌNH NGƯỜI DÙNG HIỆN TẠI (currentUser)
           ---------------------------------------------------------------------
           Đây là nơi DUY NHẤT cần thay đổi khi nối với hệ thống đăng nhập thật.
           Hiện tại đang đặt cứng để minh hoạ logic phân quyền. Khi có backend,
           hãy lấy thông tin này từ session/token đăng nhập thật, ví dụ:

               const currentUser = await fetchCurrentUser(); // null nếu chưa đăng nhập

           currentUser có 3 trạng thái:
             - null                          => Khách, chưa đăng nhập
             - { id, role: 'user' }          => Người dùng thường, đã đăng nhập
             - { id, role: 'admin' }         => Quản trị viên (admin)

           QUY TẮC PHÂN QUYỀN:
             - Khách (null):       không thấy nút Sửa/Xoá nào; muốn bình luận
                                    phải đăng nhập trước.
             - User (role:'user'): thấy nút Sửa + Xoá CHỈ trên bình luận có
                                    author-id === currentUser.id (bình luận của
                                    chính họ). Không thấy nút trên bình luận
                                    người khác.
             - Admin (role:'admin'): thấy nút Xoá trên TẤT CẢ bình luận (kể cả
                                    của mình), nhưng KHÔNG được thấy nút Sửa
                                    trên bất kỳ bình luận nào (admin không có
                                    quyền sửa nội dung bình luận của người khác
                                    hay của chính mình theo yêu cầu).
           ========================================================================= */
        const currentUser = (window.SERVER_SESSION && window.SERVER_SESSION.loggedIn)
            ? { id: String(window.SERVER_SESSION.id), role: window.SERVER_SESSION.role || 'user' }
            : null;
        // currentUser giờ lấy từ PHP session thật (thông qua window.SERVER_SESSION
        // mà header.php in ra) — không cần sửa tay ở đây nữa. Yêu cầu: trang bài
        // viết PHẢI include header.php (hoặc ít nhất load đoạn script khai báo
        // window.SERVER_SESSION) TRƯỚC khi load Article.js, nếu không currentUser
        // sẽ luôn là null (coi như khách).

        // Biến lưu tạm thẻ comment-card đang được chờ xoá (sau khi người dùng bấm "Xoá")
        let _pendingDeleteCard = null;

        // Biến lưu tạm thẻ comment-card đang được báo cáo (sau khi bấm "Báo cáo")
        let _pendingReportCard = null;

        /**
         * Áp dụng phân quyền: ẩn/hiện nút Sửa, Xoá trên từng bình luận
         * và khoá/mở khung gửi bình luận, dựa trên currentUser.
         * Hàm này chạy khi tải trang.
         */
        function applyPermissions() {
            const cards = document.querySelectorAll('.comment-card');

            cards.forEach((card) => {
                const authorId = card.dataset.authorId;
                const editBtn = card.querySelector('.action-btn.edit-btn');
                const deleteBtn = card.querySelector('.action-btn.delete-btn');

                const isOwner = !!currentUser && currentUser.id === authorId;
                const isAdmin = !!currentUser && currentUser.role === 'admin';

                // Quyền XOÁ: chủ bình luận (user) hoặc admin (bất kỳ bình luận nào)
                const canDelete = isOwner || isAdmin;

                // Quyền SỬA: CHỈ chủ bình luận, và chỉ khi họ không phải admin
                // (admin không được sửa bình luận, kể cả của chính mình)
                const canEdit = isOwner && !isAdmin;

                if (editBtn) {
                    editBtn.style.display = canEdit ? '' : 'none';
                }
                if (deleteBtn) {
                    deleteBtn.style.display = canDelete ? '' : 'none';
                }
            });

            applyCommentFormGate();
        }

        /**
         * Khoá khung gửi bình luận nếu là khách (chưa đăng nhập):
         * vẫn cho xem ô nhập, nhưng khi gửi sẽ bị chặn và nhắc đăng nhập.
         * Đồng thời đổi nội dung ghi chú nhỏ dưới khung để khách biết trước.
         */
        function applyCommentFormGate() {
            const note = document.getElementById('commentFormNote');
            if (!currentUser && note) {
                note.innerHTML = '⚠️ Bạn cần <a href="/web/DangNhap.php" style="color:#3b7dd3;font-weight:600;">đăng nhập</a> để gửi bình luận.';
            }
        }

        /**
         * Xử lý khi bấm nút "Gửi bình luận".
         * Nếu là khách (chưa đăng nhập) => chặn gửi, hiện cảnh báo và đề nghị đăng nhập.
         * Nếu đã đăng nhập (user hoặc admin) => cho gửi bình luận (demo: alert).
         */
        function handleSubmitComment(event) {
            event.preventDefault();

            if (!currentUser) {
                const wantsLogin = confirm('Bạn cần đăng nhập để gửi bình luận.\n\nBấm "OK" để đến trang đăng nhập, hoặc "Hủy" để tiếp tục xem bài viết.');
                if (wantsLogin) {
                    window.location.href = '/web/DangNhap.php';
                }
                return;
            }

            const textEl = document.getElementById('c-text');
            const content = textEl ? textEl.value.trim() : '';

            if (!content) {
                alert('Vui lòng nhập nội dung bình luận.');
                return;
            }

            // TODO: Khi có backend, gọi API gửi bình luận tại đây, ví dụ:
            // fetch('/api/comments', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ content, authorId: currentUser.id })
            // });

            alert('Bình luận của bạn đã được gửi! (demo)');
            if (textEl) textEl.value = '';
        }

        /* =========================================================================
           TÍNH NĂNG: THÍCH (LIKE) BÌNH LUẬN
           ---------------------------------------------------------------------
           Theo yêu cầu: bất kỳ ai cũng được thích, kể cả khách chưa đăng nhập.
           Đây là toggle: bấm lần 1 = thích (+1), bấm lần 2 = bỏ thích (-1).
           ========================================================================= */
        function toggleLikeComment(btn) {
            const countEl = btn.querySelector('.like-count');
            if (!countEl) return;

            const isLiked = btn.classList.contains('liked');
            let count = parseInt(countEl.textContent, 10);
            if (isNaN(count)) count = 0;

            if (isLiked) {
                // Đang thích -> bấm để bỏ thích
                btn.classList.remove('liked');
                count = Math.max(0, count - 1);
            } else {
                // Chưa thích -> bấm để thích
                btn.classList.add('liked');
                count = count + 1;
            }

            countEl.textContent = count;

            // Hiệu ứng nhỏ khi bấm cho cảm giác phản hồi tốt hơn
            btn.classList.remove('like-anim');
            // Buộc reflow để animation chạy lại mỗi lần bấm
            void btn.offsetWidth;
            btn.classList.add('like-anim');

            // TODO: Khi có backend, gọi API tại đây, ví dụ:
            // fetch('/api/comments/' + btn.closest('.comment-card').dataset.commentId + '/like', {
            //     method: isLiked ? 'DELETE' : 'POST'
            // });
        }

        /* =========================================================================
           TÍNH NĂNG: TRẢ LỜI BÌNH LUẬN
           ---------------------------------------------------------------------
           Khách (chưa đăng nhập): bấm "Trả lời" sẽ bị chặn, có thông báo yêu cầu
           đăng nhập trước (đồng nhất quy tắc với khung gửi bình luận chính).
           Người dùng/Admin đã đăng nhập: hiện form nhỏ ngay dưới bình luận để
           gõ trả lời tại chỗ.
           ========================================================================= */
        function toggleReplyForm(btn) {
            if (!currentUser) {
                const wantsLogin = confirm('Bạn cần đăng nhập để trả lời bình luận.\n\nBấm "OK" để đến trang đăng nhập, hoặc "Hủy" để tiếp tục xem bài viết.');
                if (wantsLogin) {
                    window.location.href = '/web/DangNhap.php';
                }
                return;
            }

            const card = btn.closest('.comment-card');
            if (!card) return;

            // Nếu form trả lời đã mở thì đóng lại (toggle)
            const existingForm = card.querySelector('.reply-form-box');
            if (existingForm) {
                existingForm.remove();
                return;
            }

            const formBox = document.createElement('div');
            formBox.className = 'reply-form-box';
            formBox.innerHTML = `
                <textarea class="reply-textarea" placeholder="Viết câu trả lời của bạn..."></textarea>
                <div class="reply-form-actions">
                    <button type="button" class="btn-cancel-reply">Hủy</button>
                    <button type="button" class="btn-send-reply">Gửi trả lời</button>
                </div>
            `;

            // Chèn form ngay sau nút bấm (sau .comment-actions của bình luận này)
            const actionsRow = btn.closest('.comment-actions');
            actionsRow.insertAdjacentElement('afterend', formBox);

            const textarea = formBox.querySelector('.reply-textarea');
            textarea.focus();

            formBox.querySelector('.btn-cancel-reply').addEventListener('click', () => {
                formBox.remove();
            });

            formBox.querySelector('.btn-send-reply').addEventListener('click', () => {
                sendReplyComment(card, formBox, textarea.value);
            });
        }

        /**
         * Gửi trả lời: tạo một comment-card mới trong reply-thread của bình luận gốc.
         */
        function sendReplyComment(card, formBox, value) {
            const trimmed = value.trim();
            if (!trimmed) {
                alert('Vui lòng nhập nội dung trả lời.');
                return;
            }

            // Tìm hoặc tạo reply-thread cho bình luận này
            let replyThread = card.querySelector(':scope > .reply-thread');
            if (!replyThread) {
                replyThread = document.createElement('div');
                replyThread.className = 'reply-thread';
                card.appendChild(replyThread);
            }

            const newId = 'reply_' + Date.now();
            const initials = (currentUser.id || 'U').slice(0, 2).toUpperCase();

            const replyCard = document.createElement('div');
            replyCard.className = 'comment-card is-reply';
            replyCard.dataset.commentId = newId;
            replyCard.dataset.authorId = currentUser.id;
            replyCard.innerHTML = `
                <div class="comment-header">
                    <div class="comment-avatar avatar-teal">${initials}</div>
                    <div class="comment-meta">
                        <div class="comment-author">${currentUser.role === 'admin' ? 'Quản trị viên' : 'Bạn'}</div>
                        <div class="comment-time">Vừa xong</div>
                    </div>
                </div>
                <div class="comment-text" data-original-text="${trimmed.replace(/"/g, '&quot;')}">${trimmed}</div>
                <div class="comment-actions">
                    <button class="action-btn" onclick="toggleLikeComment(this)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <span class="like-count">0</span>
                    </button>
                    <button class="action-btn edit-btn" onclick="startEditComment(this)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                        Sửa
                    </button>
                    <button class="action-btn delete-btn" onclick="confirmDeleteComment(this)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Xoá
                    </button>
                </div>
            `;
            replyThread.appendChild(replyCard);

            // Áp dụng lại phân quyền cho bình luận mới (ẩn/hiện Sửa-Xoá đúng quyền)
            applyPermissions();

            formBox.remove();
            updateCommentCount();

            // TODO: Khi có backend, gọi API tại đây, ví dụ:
            // fetch('/api/comments/' + card.dataset.commentId + '/replies', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ content: trimmed, authorId: currentUser.id })
            // });
        }

        /* =========================================================================
           TÍNH NĂNG: BÁO CÁO BÌNH LUẬN
           ---------------------------------------------------------------------
           Hiện hộp chọn lý do báo cáo (giống hộp xác nhận xoá). Ai cũng báo cáo
           được, không cần đăng nhập (báo cáo nội dung không yêu cầu tài khoản).
           ========================================================================= */
        function openReportModal(btn) {
            const card = btn.closest('.comment-card');
            if (!card) return;
            _pendingReportCard = card;

            // Reset lại lựa chọn mỗi lần mở
            const form = document.getElementById('reportModalOverlay');
            const firstRadio = form.querySelector('input[name="report-reason"]');
            if (firstRadio) firstRadio.checked = true;
            const detailTextarea = document.getElementById('reportOtherDetail');
            if (detailTextarea) detailTextarea.value = '';

            form.classList.add('show');
        }

        function cancelReportComment() {
            _pendingReportCard = null;
            document.getElementById('reportModalOverlay').classList.remove('show');
        }

        function submitReportComment() {
            if (!_pendingReportCard) {
                cancelReportComment();
                return;
            }

            const selectedRadio = document.querySelector('input[name="report-reason"]:checked');
            const reason = selectedRadio ? selectedRadio.value : 'other';
            const detail = document.getElementById('reportOtherDetail').value.trim();
            const commentId = _pendingReportCard.dataset.commentId;

            // TODO: Khi có backend, gọi API báo cáo tại đây, ví dụ:
            // fetch('/api/comments/' + commentId + '/report', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ reason, detail })
            // });

            alert('Cảm ơn bạn đã báo cáo. Chúng tôi sẽ xem xét bình luận này sớm nhất có thể.');
            cancelReportComment();
        }

        /**
         * Bắt đầu chỉnh sửa một bình luận.
         * btn: nút "Sửa" được bấm
         */
        function startEditComment(btn) {
            const card = btn.closest('.comment-card');
            if (!card) return;

            // Kiểm tra quyền lại một lần nữa trước khi cho sửa (phòng trường hợp
            // nút bị hiện ra do lỗi DOM/HTML, không chỉ dựa vào CSS display).
            const isOwner = !!currentUser && currentUser.id === card.dataset.authorId;
            const isAdmin = !!currentUser && currentUser.role === 'admin';
            if (!isOwner || isAdmin) {
                alert('Bạn không có quyền sửa bình luận này.');
                return;
            }

            // Nếu đã đang ở chế độ sửa thì bỏ qua
            if (card.querySelector('.comment-edit-box')) return;

            const textEl = card.querySelector('.comment-text');
            if (!textEl) return;

            const currentText = textEl.textContent.trim();

            // Ẩn nội dung hiện tại, tạo khung sửa
            textEl.style.display = 'none';

            const editBox = document.createElement('div');
            editBox.className = 'comment-edit-box';
            editBox.innerHTML = `
                <textarea class="edit-textarea"></textarea>
                <div class="comment-edit-actions">
                    <button type="button" class="btn-cancel-edit">Hủy</button>
                    <button type="button" class="btn-save-edit">Lưu thay đổi</button>
                </div>
            `;
            textEl.insertAdjacentElement('afterend', editBox);

            const textarea = editBox.querySelector('.edit-textarea');
            textarea.value = currentText;
            textarea.focus();
            // Đặt con trỏ ở cuối nội dung
            textarea.selectionStart = textarea.selectionEnd = textarea.value.length;

            // Nút Hủy: đóng khung sửa, không thay đổi nội dung
            editBox.querySelector('.btn-cancel-edit').addEventListener('click', () => {
                editBox.remove();
                textEl.style.display = '';
            });

            // Nút Lưu: cập nhật nội dung bình luận
            editBox.querySelector('.btn-save-edit').addEventListener('click', () => {
                saveEditedComment(card, textEl, editBox, textarea.value);
            });
        }

        /**
         * Lưu nội dung bình luận đã sửa.
         */
        function saveEditedComment(card, textEl, editBox, newValue) {
            const trimmed = newValue.trim();

            if (!trimmed) {
                alert('Nội dung bình luận không được để trống.');
                return;
            }

            textEl.textContent = trimmed;
            textEl.dataset.originalText = trimmed;
            textEl.style.display = '';
            editBox.remove();

            // Thêm nhãn "đã chỉnh sửa" nếu chưa có
            const meta = card.querySelector('.comment-meta');
            if (meta && !meta.querySelector('.comment-edited-label')) {
                const label = document.createElement('span');
                label.className = 'comment-edited-label';
                label.textContent = '(đã chỉnh sửa)';
                meta.appendChild(label);
            }

            // TODO: Khi có backend, gọi API tại đây để lưu nội dung mới, ví dụ:
            // fetch('/api/comments/' + card.dataset.commentId, {
            //     method: 'PUT',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify({ content: trimmed })
            // });
        }

        /**
         * Mở hộp xác nhận xoá bình luận.
         */
        function confirmDeleteComment(btn) {
            const card = btn.closest('.comment-card');
            if (!card) return;

            // Kiểm tra quyền lại trước khi cho xoá
            const isOwner = !!currentUser && currentUser.id === card.dataset.authorId;
            const isAdmin = !!currentUser && currentUser.role === 'admin';
            if (!isOwner && !isAdmin) {
                alert('Bạn không có quyền xoá bình luận này.');
                return;
            }

            _pendingDeleteCard = card;
            document.getElementById('deleteConfirmOverlay').classList.add('show');
        }

        /**
         * Đóng hộp xác nhận xoá, không xoá gì cả.
         */
        function cancelDeleteComment() {
            _pendingDeleteCard = null;
            document.getElementById('deleteConfirmOverlay').classList.remove('show');
        }

        /**
         * Thực hiện xoá bình luận đã được xác nhận.
         */
        function doDeleteComment() {
            if (_pendingDeleteCard) {
                const card = _pendingDeleteCard;

                // TODO: Khi có backend, gọi API xoá tại đây, ví dụ:
                // fetch('/api/comments/' + card.dataset.commentId, { method: 'DELETE' });

                // Nếu đây là bình luận gốc có chứa các trả lời (reply-thread), xoá luôn cả thread
                card.remove();
                updateCommentCount();
            }
            cancelDeleteComment();
        }

        /**
         * Cập nhật lại số lượng bình luận hiển thị ở tiêu đề mục Bình luận
         * sau khi xoá (đếm tổng số .comment-card còn lại trong .comment-list).
         */
        function updateCommentCount() {
            const countBadge = document.querySelector('.section-heading .count-badge');
            if (!countBadge) return;
            const total = document.querySelectorAll('.comment-list .comment-card').length;
            countBadge.textContent = total;
        }

        // Cho phép nhấn Esc để đóng hộp xác nhận xoá / báo cáo
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cancelDeleteComment();
                cancelReportComment();
            }
        });

        // Cho phép bấm ra ngoài overlay để hủy xoá
        document.getElementById('deleteConfirmOverlay').addEventListener('click', (e) => {
            if (e.target.id === 'deleteConfirmOverlay') {
                cancelDeleteComment();
            }
        });

        // Cho phép bấm ra ngoài overlay để hủy báo cáo
        document.getElementById('reportModalOverlay').addEventListener('click', (e) => {
            if (e.target.id === 'reportModalOverlay') {
                cancelReportComment();
            }
        });

        // Áp dụng phân quyền ngay khi trang vừa tải xong
        applyPermissions();