// Script/trangchu.js
// =============================================================================
// FILE GỘP: home.js + trangchu.js + ĐĂNG NHẬP MXH + ĐỒNG BỘ PROFILE THỰC TẾ
// (Bản tối ưu)
// =============================================================================

(function () {
    "use strict";

    // -------------------------------------------------------------------
    // Helpers dùng chung
    // -------------------------------------------------------------------
    const debounce = (fn, delay = 300) => {
        let timer = null;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    };

    const $ = (sel) => document.querySelector(sel);
    const $id = (id) => document.getElementById(id);

    // =============================================================================
    // 0. TÌM KIẾM (debounce + huỷ request cũ để tránh race-condition)
    // =============================================================================
    document.addEventListener("DOMContentLoaded", () => {
        const searchBox = $id('searchBox');
        const searchToggleBtn = $id('searchToggleBtn');
        const searchInputEl = $id('searchInput');
        const navbarInner = $('.navbar__inner');
        if (!searchBox) return;

        if (window.SERVER_SESSION && window.SERVER_SESSION.role === 'admin') {
            const links = document.querySelectorAll('.navbar__user-menu a');
            
            links.forEach(link => {
                if (link.textContent.trim() === "Tài khoản của tôi") {
                    link.href = "/web/admin_profile.php";
                    console.log("Đã đổi link thành công sang: " + link.href);
                }
            });
        }

        const suggestBox = document.createElement("div");
        suggestBox.className = "search-suggest-box";
        Object.assign(suggestBox.style, {
            position: "absolute", top: "100%", right: "0", width: "320px",
            background: "white", border: "1px solid #ddd", borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(0,0,0,0.15)", maxHeight: "250px",
            overflowY: "auto", zIndex: "9999", display: "none", marginTop: "5px",
        });
        searchBox.appendChild(suggestBox);

        let abortCtrl = null;

        const hideSuggest = () => {
            suggestBox.style.display = "none";
            suggestBox.innerHTML = "";
        };

        const clearSearch = () => {
            if (searchInputEl) searchInputEl.value = "";
            hideSuggest();
        };

        searchToggleBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            const isActive = searchBox.classList.toggle('active');
            navbarInner?.classList.toggle('search-active', isActive);
            if (isActive) searchInputEl?.focus(); else clearSearch();
        });

        const renderResults = (results) => {
            suggestBox.innerHTML = "";
            if (results.length === 0) {
                const empty = document.createElement("div");
                empty.style.cssText = "padding:10px; color:#888;";
                empty.textContent = "Không có kết quả...";
                suggestBox.appendChild(empty);
                suggestBox.style.display = "block";
                return;
            }

            const fragment = document.createDocumentFragment();
            results.forEach((item) => {
                const row = document.createElement("div");
                row.style.cssText = "padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;";
                row.textContent = item.title;
                row.onclick = () => {
                    if (item.path) {
                        window.location.href = "/web/Article/" + item.path;
                    } else {
                        console.warn("Dữ liệu bài viết này thiếu cột 'path'!", item);
                    }
                };
                fragment.appendChild(row);
            });
            suggestBox.appendChild(fragment);
            suggestBox.style.display = "block";
        };

        const performSearch = async (query) => {
            if (!query) { hideSuggest(); return; }

            // Huỷ request trước đó nếu còn đang chạy (tránh kết quả cũ ghi đè kết quả mới)
            abortCtrl?.abort();
            abortCtrl = new AbortController();

            try {
                const res = await fetch(`/web/Api_search.php?q=${encodeURIComponent(query)}`, {
                    signal: abortCtrl.signal,
                });
                const results = res.ok ? await res.json() : [];
                renderResults(results);
            } catch (e) {
                if (e.name !== "AbortError") hideSuggest();
            }
        };

        const debouncedSearch = debounce((q) => performSearch(q), 300);

        searchInputEl?.addEventListener("input", () => {
            debouncedSearch(searchInputEl.value.trim());
        });

        searchInputEl?.addEventListener("keydown", (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(searchInputEl.value.trim()); // Enter tìm ngay, bỏ qua debounce
            }
        });

        document.addEventListener('click', (e) => {
            if (!searchBox.contains(e.target)) {
                searchBox.classList.remove('active');
                navbarInner?.classList.remove('search-active');
                clearSearch();
            }
        });
    });

    // =============================================================================
    // 1. ĐĂNG NHẬP / ĐĂNG XUẤT - HIỂN THỊ MENU NGƯỜI DÙNG
    // =============================================================================
    const loginBtn = $('.navbar__login-btn');
    const userMenu = $('.navbar__user');
    const userMenuToggle = $id('userMenuToggle');
    const logoutBtn = $id('logoutBtn') || $('.logout');

    // Đọc toàn bộ localStorage liên quan MỘT LẦN, tránh gọi getItem lặp lại
    // Kết hợp thêm trạng thái đăng nhập THẬT từ PHP session (window.SERVER_SESSION,
    // được header.php in ra) — vì đăng nhập bằng form thường chỉ tạo session,
    // không hề ghi gì vào localStorage. Nếu chỉ dựa vào localStorage, JS sẽ tưởng
    // "chưa đăng nhập" và tự ẩn mất menu mà PHP đã render đúng.
    function getAuthState() {
        const server = window.SERVER_SESSION || { loggedIn: false, fullname: null };

        const token = localStorage.getItem('jwt_token');
        let normalUser = null;
        try { normalUser = JSON.parse(localStorage.getItem('user_info')); } catch (e) { normalUser = null; }

        const isSocialLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        const loginType = localStorage.getItem('loginType');

        // Ưu tiên: session PHP > JWT thường > đăng nhập MXH qua localStorage
        const isLoggedIn = server.loggedIn || !!(token && normalUser) || isSocialLoggedIn;

        let displayName = "Thành viên";
        if (server.loggedIn && server.fullname) {
            displayName = server.fullname;
        } else if (token && normalUser) {
            displayName = normalUser.fullname || "Thành viên";
        } else if (isSocialLoggedIn) {
            displayName = localStorage.getItem('userName') || "Thành viên";
        }

        const displayAvatar = localStorage.getItem('userAvatar') || "";

        return { token, normalUser, isSocialLoggedIn, loginType, isLoggedIn, displayName, displayAvatar, serverLoggedIn: server.loggedIn };
    }

    function renderAuthState() {
        const { token, normalUser, loginType, isLoggedIn, displayName, displayAvatar } = getAuthState();

        if (loginBtn) loginBtn.style.display = isLoggedIn ? 'none' : '';

        if (userMenu) {
            userMenu.style.display = isLoggedIn ? 'flex' : 'none';

            const nameTextEl = $id("userName");
            if (nameTextEl && isLoggedIn) nameTextEl.textContent = displayName;

            const avatarEl = $id("userAvatar");
            if (avatarEl && isLoggedIn) {
                if (displayAvatar) {
                    avatarEl.style.backgroundImage = `url('${displayAvatar}')`;
                    avatarEl.style.backgroundSize = "cover";
                    avatarEl.style.backgroundPosition = "center";
                    avatarEl.innerHTML = "";
                } else {
                    avatarEl.style.backgroundImage = "none";
                    avatarEl.innerHTML = "";
                    avatarEl.textContent = displayName.charAt(0).toUpperCase();
                }
            }
        }

        if (window.location.pathname.toLowerCase().includes("profile.php") && isLoggedIn) {
            syncProfilePage({ token, normalUser, loginType, displayName, displayAvatar });
        }
    }

    // Tách riêng logic trang profile để renderAuthState gọn hơn, dễ bảo trì
    function syncProfilePage({ token, normalUser, loginType, displayName, displayAvatar }) {
        const profileForm = $id("profileForm");
        const fullNameInput = $id("fullName");
        const emailInput = $id("email");
        const phoneInput = $id("phone");
        const avatarPreview = $id("avatarPreview");
        const passwordForm = $id("passwordForm");

        if (fullNameInput) fullNameInput.value = displayName;
        if (emailInput) emailInput.value = localStorage.getItem("registeredEmail") || (normalUser ? normalUser.email : "");
        if (phoneInput) phoneInput.value = localStorage.getItem("userPhone") || "";
        if (avatarPreview && displayAvatar) avatarPreview.src = displayAvatar;

        profileForm?.addEventListener("submit", (e) => {
            e.preventDefault();
            const newName = fullNameInput.value.trim();
            const newEmail = emailInput.value.trim();
            const newPhone = phoneInput.value.trim();

            if (!newName) {
                alert("❌ Họ và tên không được để trống!");
                return;
            }

            if (token && normalUser) {
                normalUser.fullname = newName;
                normalUser.email = newEmail;
                localStorage.setItem("user_info", JSON.stringify(normalUser));
            } else {
                localStorage.setItem("userName", newName);
                localStorage.setItem("registeredEmail", newEmail);
            }
            localStorage.setItem("userPhone", newPhone);

            alert("🎉 Đã đồng bộ họ tên và thông tin mới thành công!");
            window.location.reload();
        });

        if (loginType === 'google' || loginType === 'facebook') {
            const passwordSection = passwordForm?.closest(".card");
            if (passwordSection) passwordSection.style.display = "none";
        }
    }

    loginBtn?.addEventListener('click', () => {
        sessionStorage.setItem('redirectAfterLogin', window.location.href);
    });

    userMenuToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenu?.classList.toggle('is-open');
    });

    document.addEventListener('click', (e) => {
        if (userMenu && !userMenu.contains(e.target)) {
            userMenu.classList.remove('is-open');
        }
    });

    logoutBtn?.addEventListener('click', async (e) => {
        e.preventDefault();
        localStorage.clear();
        sessionStorage.clear();
        userMenu?.classList.remove('is-open');

        try {
            // Gọi ngầm để huỷ session PHP, không cần chờ điều hướng qua DangNhap.php
            await fetch('/web/DangNhap.php?action=logout', { credentials: 'same-origin' });
        } catch (err) {
            console.error('Lỗi khi huỷ phiên đăng nhập:', err);
        }

        window.location.href = '/web/Trangchu.php';
    });

    renderAuthState();

    // =============================================================================
    // PHÂN QUYỀN ADMIN
    // =============================================================================
    (function checkAdminAccess() {
        const currentPage = window.location.pathname.toLowerCase();
        if (!currentPage.includes("admin_")) return;

        const { normalUser, isSocialLoggedIn } = getAuthState();
        const userRole = normalUser?.role || localStorage.getItem("userRole");

        if ((!normalUser?.fullname && !isSocialLoggedIn) || userRole !== "admin") {
            alert("⛔ Bạn không có quyền truy cập trang này!");
            window.location.href = "./Trangchu.php";
        }
    })();

    // =========================================================================
    // 5. XỬ LÝ ĐĂNG KÝ NHẬN TIN TẠI FOOTER
    // =========================================================================
    const newsletterInput = $('.footer__newsletter-input');
    const newsletterBtn = $('.footer__newsletter-btn');

    if (newsletterBtn && newsletterInput) {
        const handleSubscribe = () => {
            const emailValue = newsletterInput.value.trim();
            if (!emailValue) { alert("Vui lòng nhập địa chỉ email."); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) { alert("Email không hợp lệ."); return; }

            alert(`Thành công! Email ${emailValue} đã đăng ký nhận bản tin.`);
            newsletterInput.value = "";
        };

        newsletterBtn.addEventListener("click", (e) => { e.preventDefault(); handleSubscribe(); });
        newsletterInput.addEventListener("keypress", (e) => { if (e.key === "Enter") { e.preventDefault(); handleSubscribe(); } });
    }

})();