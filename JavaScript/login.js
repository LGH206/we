document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const togglePw = document.getElementById('togglePw');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    // 1. Xử lý Đăng nhập (Sử dụng Async/Await cho gọn gàng)
    loginForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch('DangNhap.php', {
                method: 'POST',
                body: new FormData(loginForm)
            });

            // Kiểm tra xem phản hồi có phải là JSON không
            const data = await response.json().catch(() => {
                throw new Error("Phản hồi từ máy chủ không hợp lệ.");
            });

            if (data.status === 'success') {
                alert(data.message);
                window.location.href = 'Trangchu.php';
            } else {
                alert(data.message || 'Đăng nhập thất bại.');
            }
        } catch (error) {
            console.error('Lỗi hệ thống:', error);
            alert('Lỗi kết nối máy chủ. Vui lòng kiểm tra XAMPP.');
        }
    });

    // 2. Xử lý hiện/ẩn mật khẩu (Tối ưu logic chuyển đổi)
    togglePw?.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.textContent = isPassword ? '🙈' : '👁️';
    });

    // 3. Xử lý Đăng nhập mạng xã hội (Sử dụng Event Delegation để tối ưu hiệu năng)
    document.querySelector('.social')?.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains('btn-google-login')) {
            alert('Tính năng đăng nhập Google đang được phát triển!');
        } else if (btn.classList.contains('btn-facebook-login')) {
            alert('Tính năng đăng nhập Facebook đang được phát triển!');
        }
    });
});