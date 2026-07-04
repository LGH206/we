document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');

    // 1. Xử lý Đăng ký với Async/Await và kiểm tra JSON an toàn
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch('DangKy.php', {
                method: 'POST',
                body: new FormData(form)
            });

            // Kiểm tra phản hồi có phải là JSON hợp lệ không
            const data = await response.json().catch(() => {
                throw new Error("Phản hồi từ máy chủ không hợp lệ");
            });

            alert(data.message);
            if (data.status === 'success') {
                window.location.href = 'DangNhap.php';
            }
        } catch (err) {
            console.error('Lỗi:', err);
            alert('Có lỗi xảy ra khi kết nối tới máy chủ.');
        }
    });

    // 2. Tối ưu hóa hàm xử lý ẩn/hiện mật khẩu
    const setupToggle = (inputId, iconId) => {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        // Tìm nút cha ngay gần nhất là toggle button
        const btn = icon.parentElement; 
        btn.addEventListener('click', () => {
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.textContent = isPass ? '🙈' : '👁️';
        });
    };

    setupToggle('password', 'eyeIcon');
    setupToggle('confirm', 'eyeIcon2');

    // 3. Tối ưu hóa Đăng ký qua MXH bằng Event Delegation
    // Gắn sự kiện vào thẻ cha để quản lý tất cả các nút con
    document.querySelector('.social')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-social');
        if (!btn) return;

        if (btn.classList.contains('btn-google-regis')) {
            alert('Tính năng đăng ký bằng Google đang được phát triển!');
        } else if (btn.classList.contains('btn-facebook-regis')) {
            alert('Tính năng đăng ký bằng Facebook đang được phát triển!');
        }
    });
});