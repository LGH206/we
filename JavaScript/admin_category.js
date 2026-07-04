/* ============================================================
   DATA
============================================================ */
const navItems = [
  { icon: "user", label: "Profile" },
  { icon: "layout-dashboard", label: "Bảng điều khiển" },
  { icon: "users", label: "Quản lý người dùng" },
  { icon: "message-square", label: "Quản lý bình luận" },
  { icon: "file-text", label: "Quản lý bài viết" },
  { icon: "folder-tree", label: "Quản lý danh mục", active: true },
];
const categories = [
  { icon: "heart", name: "Tim mạch",
    desc: "Sức khoẻ tim mạch, các phương pháp điều trị và nghiên cứu phòng ngừa.",
    posts: 342, updated: "2 giờ trước" },
  { icon: "apple", name: "Dinh dưỡng",
    desc: "Hướng dẫn chế độ ăn uống, thực phẩm bổ sung và thói quen ăn uống lành mạnh.",
    posts: 215, updated: "1 ngày trước" },
  { icon: "brain", name: "Hệ thần kinh",
    desc: "Tâm lý học, quản lý căng thẳng và hướng dẫn sức khoẻ cảm xúc.",
    posts: 189, updated: "3 giờ trước" },
  { icon: "stethoscope", name: "Nhi khoa",
    desc: "Sức khoẻ trẻ em.",
    posts: 124, updated: "5 ngày trước" },
  { icon: "activity", name: "Tiêu hoá",
    desc: "Sức khoẻ đường tiêu hoá, nghiên cứu hệ vi sinh và chăm sóc tiêu hoá.",
    posts: 96, updated: "1 tuần trước" },
];
const recent = [
  { icon: "dumbbell",   name: "Thể chất",         slug: "fitness_center", status: "CÔNG KHAI", type: "public" },
  { icon: "microscope", name: "Nghiên cứu y học", slug: "biotech",        status: "NỘI BỘ",    type: "internal" },
];
/* ============================================================
   RENDER
============================================================ */
const i = (name) => `<i class="icon-${name}"></i>`;
function renderNav() {
  const el = document.getElementById("mainNav");
  el.innerHTML = navItems.map(n => `
    <a href="#" class="nav-item ${n.active ? "active" : ""}">
      ${i(n.icon)} ${n.label}
    </a>
  `).join("");
}
function renderCategories() {
  const el = document.getElementById("categoryGrid");
  document.getElementById("totalCount").textContent = "24";
  const cards = categories.map(c => `
    <article class="card">
      <div class="card-head">
        <div class="card-icon">${i(c.icon)}</div>
        <div class="card-actions">
          <button class="action-btn" title="Sửa">${i("pencil")}</button>
          <button class="action-btn danger" title="Xoá">${i("trash-2")}</button>
        </div>
      </div>
      <h3>${c.name}</h3>
      <p class="card-desc">${c.desc}</p>
      <div class="card-foot">
        <span class="badge">${c.posts} Bài viết</span>
        <span class="card-meta">Cập nhật lần cuối: ${c.updated}</span>
      </div>
    </article>
  `).join("");
  el.innerHTML = cards + `
    <button class="card-add" id="btnSeeMore">
      ${i("plus")}
      <span>Xem thêm danh mục</span>
    </button>
  `;
}
function renderRecent() {
  const el = document.getElementById("recentBody");
  el.innerHTML = recent.map(r => `
    <tr>
      <td><div class="cell-name">${i(r.icon)} ${r.name}</div></td>
      <td style="color:var(--muted)">${r.slug}</td>
      <td><span class="tag tag-${r.type}">${r.status}</span></td>
      <td><a href="#" class="link-action">Quản lý</a></td>
    </tr>
  `).join("");
}
/* ============================================================
   EVENTS
============================================================ */
function bindEvents() {
  document.getElementById("btnAdd")?.addEventListener("click", () => {
    alert("Mở form thêm danh mục mới");
  });
  document.addEventListener("click", (e) => {
    const card = e.target.closest(".card");
    if (e.target.closest(".action-btn.danger") && card) {
      const name = card.querySelector("h3")?.textContent;
      if (confirm(`Xoá danh mục "${name}"?`)) card.remove();
    }
  });
}
/* ============================================================
   INIT
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  renderNav();
  renderCategories();
  renderRecent();
  bindEvents();
});