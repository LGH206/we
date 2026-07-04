/**
 * admin_profile.js
 * Xử lý: Cập nhật thông tin cá nhân, đổi mật khẩu, upload avatar, độ mạnh mật khẩu
 * ĐÃ SỬA: Dùng ID cụ thể thay vì querySelector mù, thêm current_password, toast thay alert
 */

document.addEventListener('DOMContentLoaded', () => {

    // =============================================
    // HELPER: Gửi request lên server
    // =============================================
    async function sendRequest(formData) {
        try {
            const response = await fetch('Admin_profile.php', {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch {
                console.error('Server trả về không phải JSON:', text);
                return { success: false, message: 'Server trả về dữ liệu không hợp lệ.' };
            }
        } catch (error) {
            console.error('Lỗi kết nối:', error);
            return { success: false, message: 'Không thể kết nối server, vui lòng thử lại.' };
        }
    }

    // HELPER: Hiển thị toast thông báo thay vì alert()
    function showToast(message, type = 'success') {
        // Tạo toast nếu chưa có container
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = `
                position: fixed; top: 20px; right: 20px; z-index: 9999;
                display: flex; flex-direction: column; gap: 10px;
            `;
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const bg = type === 'success' ? '#2ecc71' : '#e74c3c';
        toast.style.cssText = `
            background: ${bg}; color: #fff; padding: 12px 20px; border-radius: 8px;
            font-size: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease; min-width: 260px;
        `;
        toast.textContent = message;
        container.appendChild(toast);

        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // HELPER: Lấy giá trị input theo ID, trả về '' nếu không tìm thấy
    function val(id) {
        const el = document.getElementById(id);
        return el ? el.value.trim() : '';
    }

    // =============================================
    // 1. CẬP NHẬT THÔNG TIN CÁ NHÂN
    // =============================================
    document.getElementById('saveProfile')?.addEventListener('click', async () => {
        const fullname = val('profileFullname'); // <input id="profileFullname">
        const phone    = val('profilePhone');    // <input id="profilePhone">

        if (!fullname) {
            return showToast('Vui lòng nhập họ tên!', 'error');
        }

        const formData = new FormData();
        formData.append('action',   'update_info');
        formData.append('fullname', fullname);
        formData.append('phone',    phone);

        const btn = document.getElementById('saveProfile');
        btn.disabled = true;
        btn.textContent = 'Đang lưu...';

        const result = await sendRequest(formData);
        showToast(result.message, result.success ? 'success' : 'error');

        btn.disabled = false;
        btn.textContent = 'Lưu thay đổi';
    });

    // =============================================
    // 2. ĐỔI MẬT KHẨU
    // =============================================
    document.getElementById('updatePwd')?.addEventListener('click', async () => {
        const currentPwd = val('currentPwd'); // <input id="currentPwd">
        const newPwd     = val('newPwd');     // <input id="newPwd">
        const confirmPwd = val('confirmPwd'); // <input id="confirmPwd">

        // Validation
        if (!currentPwd) return showToast('Vui lòng nhập mật khẩu hiện tại!', 'error');
        if (newPwd.length < 6) return showToast('Mật khẩu mới phải có ít nhất 6 ký tự!', 'error');
        if (newPwd !== confirmPwd) return showToast('Mật khẩu xác nhận không khớp!', 'error');
        if (currentPwd === newPwd) return showToast('Mật khẩu mới không được trùng mật khẩu cũ!', 'error');

        const formData = new FormData();
        formData.append('action',           'update_password');
        formData.append('current_password', currentPwd);
        formData.append('new_password',     newPwd);

        const btn = document.getElementById('updatePwd');
        btn.disabled = true;
        btn.textContent = 'Đang cập nhật...';

        const result = await sendRequest(formData);
        showToast(result.message, result.success ? 'success' : 'error');

        if (result.success) {
            // Xóa trắng các ô mật khẩu sau khi đổi thành công
            ['currentPwd', 'newPwd', 'confirmPwd'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            // Reset thanh độ mạnh
            resetStrengthBar();
        }

        btn.disabled = false;
        btn.textContent = 'Cập nhật mật khẩu';
    });

    // =============================================
    // 3. ẨN/HIỆN MẬT KHẨU (Toggle password visibility)
    // =============================================
    document.querySelectorAll('.toggle-pwd').forEach(btn => {
        btn.addEventListener('click', () => {
            // Tìm input trong cùng wrapper cha (.input-wrap hoặc .field)
            const wrapper = btn.closest('.input-wrap') || btn.parentElement;
            const input = wrapper?.querySelector('input[type="password"], input[type="text"]');
            if (!input) return;
            input.type = (input.type === 'password') ? 'text' : 'password';
            btn.textContent = (input.type === 'password') ? '👁' : '🙈';
        });
    });

    // =============================================
    // 4. ĐỘ MẠNH MẬT KHẨU (3 cấp: Yếu / Trung bình / Mạnh)
    // =============================================
    const newPwdInput  = document.getElementById('newPwd');
    const strengthFill  = document.getElementById('strengthFill');
    const strengthLabel = document.getElementById('strengthLabel');

    function resetStrengthBar() {
        if (strengthFill)  { strengthFill.style.width = '0%'; strengthFill.style.backgroundColor = '#ccc'; }
        if (strengthLabel) strengthLabel.textContent = '';
    }

    newPwdInput?.addEventListener('input', () => {
        const val = newPwdInput.value;
        let score = 0;

        if (val.length >= 8)          score++; // Đủ dài
        if (val.length >= 12)         score++; // Rất dài
        if (/[A-Z]/.test(val))        score++; // Chữ hoa
        if (/[a-z]/.test(val))        score++; // Chữ thường
        if (/[0-9]/.test(val))        score++; // Số
        if (/[!@#$%^&*]/.test(val))   score++; // Ký tự đặc biệt

        let width = 0, color = '#ccc', label = '';

        if (val.length === 0) {
            resetStrengthBar(); return;
        } else if (score <= 2) {
            width = 33;  color = '#e74c3c'; label = 'Yếu';
        } else if (score <= 4) {
            width = 66;  color = '#f39c12'; label = 'Trung bình';
        } else {
            width = 100; color = '#2ecc71'; label = 'Mạnh';
        }

        if (strengthFill)  { strengthFill.style.width = width + '%'; strengthFill.style.backgroundColor = color; }
        if (strengthLabel) { strengthLabel.textContent = label; strengthLabel.style.color = color; }
    });

    // =============================================
    // 5. UPLOAD AVATAR – Preview trước khi lưu
    // =============================================
    const avatarInput   = document.getElementById('avatarInput');   // <input type="file" id="avatarInput">
    const avatarPreview = document.getElementById('avatarPreview'); // <img id="avatarPreview">
    const saveAvatarBtn = document.getElementById('saveAvatar');    // <button id="saveAvatar">

    avatarInput?.addEventListener('change', () => {
        const file = avatarInput.files[0];
        if (!file) return;

        // Kiểm tra định dạng
        if (!file.type.startsWith('image/')) {
            return showToast('Vui lòng chọn file ảnh hợp lệ!', 'error');
        }
        // Kiểm tra dung lượng (tối đa 2MB)
        if (file.size > 2 * 1024 * 1024) {
            return showToast('Ảnh không được vượt quá 2MB!', 'error');
        }

        // Hiển thị preview
        const reader = new FileReader();
        reader.onload = e => {
            if (avatarPreview) avatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    saveAvatarBtn?.addEventListener('click', async () => {
        const file = avatarInput?.files[0];
        if (!file) return showToast('Vui lòng chọn ảnh trước!', 'error');

        const formData = new FormData();
        formData.append('action', 'update_avatar');
        formData.append('avatar', file);

        saveAvatarBtn.disabled = true;
        saveAvatarBtn.textContent = 'Đang tải lên...';

        const result = await sendRequest(formData);
        showToast(result.message, result.success ? 'success' : 'error');

        saveAvatarBtn.disabled = false;
        saveAvatarBtn.textContent = 'Lưu ảnh đại diện';
    });
});