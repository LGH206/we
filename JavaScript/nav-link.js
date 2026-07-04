/* ============================================================
   nav-link.js — Liên kết các trang admin với nhau
   - Wire sidebar nav items (theo text label) tới đúng trang HTML
   - Wire nút "Đăng xuất" về trang đăng nhập
   - Hoạt động sau khi các JS khác đã render xong DOM
============================================================ */
(function () {
  const LABEL_TO_HREF = {
    "profile": "Admin_profile.php",
    "bảng điều khiển": "Admin_dashboard.php",
    "quản lý người dùng": "Admin_user.php",
    "quản lý bình luận": "Admin_comment.php",
    "quản lý bài viết": "Admin_post.php",
    "quản lý danh mục": "Admin_category.php",
    "đăng xuất": "Trangchu.php",
  };

  function normalize(s) {
    return (s || "").trim().toLowerCase().replace(/\s+/g, " ");
  }

  function wire() {
    const items = document.querySelectorAll(
      ".nav-item, aside .nav a, aside .nav button, #nav-list a, #mainNav a"
    );
    items.forEach((el) => {
      const text = normalize(el.textContent);
      let href = null;
      for (const key in LABEL_TO_HREF) {
        if (text.includes(key)) { href = LABEL_TO_HREF[key]; break; }
      }
      if (!href) return;

      if (el.tagName === "A") {
        el.setAttribute("href", href);
      } else {
        el.style.cursor = "pointer";
        el.addEventListener("click", (e) => {
          e.preventDefault();
          window.location.href = href;
        });
      }
    });
  }

  // Chạy sau khi DOM sẵn sàng và sau khi các script render nav khác đã chạy
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => setTimeout(wire, 0));
  } else {
    setTimeout(wire, 0);
  }
})();
