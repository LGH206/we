// JavaScript/forget_password.js

function showMessage(text, isError = true) {
    const messageEl = document.getElementById('message');
    messageEl.textContent = text;
    messageEl.style.color = isError ? '#e53935' : '#2e7d32';
}

function clearMessage() {
    const messageEl = document.getElementById('message');
    messageEl.textContent = '';
}

// ---------- BƯỚC 1: KIỂM TRA EMAIL ----------
async function checkEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();

    clearMessage();

    if (!email) {
        showMessage('Vui lòng nhập email.');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'check_email');
        formData.append('email', email);

        const res = await fetch('Forget_password.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json().catch(() => {
            throw new Error('Phản hồi từ máy chủ không hợp lệ.');
        });

        if (data.status === 'success') {
            document.getElementById('email-step').style.display = 'none';
            document.getElementById('password-step').style.display = 'block';
            clearMessage();
        } else {
            showMessage(data.message || 'Email không hợp lệ.');
        }
    } catch (error) {
        console.error('Lỗi kiểm tra email:', error);
        showMessage('Lỗi kết nối máy chủ. Vui lòng thử lại.');
    }
}

// ---------- BƯỚC 2: ĐỔI MẬT KHẨU ----------
async function submitReset() {
    const email = document.getElementById('email').value.trim();
    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    clearMessage();

    if (!oldPassword || !newPassword || !confirmPassword) {
        showMessage('Vui lòng nhập đầy đủ các trường mật khẩu.');
        return;
    }

    if (newPassword !== confirmPassword) {
        showMessage('Mật khẩu mới nhập lại không khớp.');
        return;
    }

    if (newPassword.length < 6) {
        showMessage('Mật khẩu mới phải có ít nhất 6 ký tự.');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'reset_password');
        formData.append('email', email);
        formData.append('old_password', oldPassword);
        formData.append('new_password', newPassword);
        formData.append('confirm_password', confirmPassword);

        const res = await fetch('Forget_password.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json().catch(() => {
            throw new Error('Phản hồi từ máy chủ không hợp lệ.');
        });

        if (data.status === 'success') {
            showMessage(data.message + ' Đang chuyển tới trang đăng nhập...', false);
            setTimeout(() => {
                window.location.href = 'DangNhap.php';
            }, 1500);
        } else {
            showMessage(data.message || 'Đổi mật khẩu thất bại.');
        }
    } catch (error) {
        console.error('Lỗi đổi mật khẩu:', error);
        showMessage('Lỗi kết nối máy chủ. Vui lòng thử lại.');
    }
}