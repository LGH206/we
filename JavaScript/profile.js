document.addEventListener("DOMContentLoaded", function () {
    // 1. XỬ LÝ ĐỔI ẢNH ĐẠI DIỆN
    const avatarInput = document.getElementById('avatarInput');
    const avatarEditBtn = document.getElementById('avatarEdit');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarEditBtn && avatarInput) {
        avatarEditBtn.addEventListener('click', () => avatarInput.click());

        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    avatarPreview.src = event.target.result;
                    // Lưu ảnh vào localStorage (nếu không dùng upload server)
                    localStorage.setItem('userAvatar', event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 2. XỬ LÝ HIỆN/ẨN MẬT KHẨU
    const toggleButtons = document.querySelectorAll('.toggle-pw');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.querySelector('span').textContent = '🙈';
            } else {
                input.type = 'password';
                this.querySelector('span').textContent = '👁️';
            }
        });
    });

    // 3. XỬ LÝ ĐỘ MẠNH MẬT KHẨU
    const newPassword = document.getElementById('newPassword');
    const strengthFill = document.querySelector('.strength-fill');

    newPassword?.addEventListener('input', function () {
        const val = this.value;
        let strength = 0;
        if (val.length > 5) strength += 33;
        if (/[A-Z]/.test(val)) strength += 33;
        if (/[0-9!@#$%^&*]/.test(val)) strength += 34;
        
        strengthFill.style.width = strength + '%';
        strengthFill.style.backgroundColor = strength < 50 ? '#ff4d4d' : '#2ecc71';
    });

    // 4. XỬ LÝ GỬI FORM (Giả lập update)
    const profileForm = document.getElementById('profileForm');
    profileForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(profileForm);
        
        const res = await fetch('/web/Profile.php', { method: 'POST', body: formData });
        const data = await res.json();
        console.log(data);
        alert(data.message);
    });

    // 3. Cập nhật mật khẩu
    const passwordForm = document.getElementById('passwordForm');
    passwordForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(passwordForm);
        
        if (formData.get('newPassword') !== formData.get('confirmPassword')) {
            alert('Mật khẩu xác nhận không khớp!');
            return;
        }

        const res = await fetch('/web/Profile.php', { method: 'POST', body: formData });
        const data = await res.json();
        console.log(data);
        alert(data.message);
    });
});