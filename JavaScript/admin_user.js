/* ============================================
   user.js — Trang Quản lý người dùng
   - Render sidebar nav
   - Render bảng người dùng
   - Filter, search, pagination, xoá (demo)
   ============================================ */
// ---------- Dữ liệu mẫu ----------
const NAV_ITEMS = [
  { icon: 'user',            label: 'Profile' },
  { icon: 'layout-dashboard', label: 'Bảng điều khiển' },
  { icon: 'users',            label: 'Quản lý người dùng', active: true },
  { icon: 'message-square',   label: 'Quản lý bình luận' },
  { icon: 'file-text',        label: 'Quản lý bài viết' },
  { icon: 'folder-tree',      label: 'Quản lý danh mục' },
];
const USERS = [
  { id: '#8801', name: 'Sarah Jenkins',    email: 's.jenkins@example.com',        status: 'active', role: 'user',  avatarBg: 'hsl(145, 45%, 70%)' },
  { id: '#9022', name: 'David Chen',       email: 'david.c@healthmail.net',       status: 'paused', role: 'admin', avatarBg: 'hsl(35, 60%, 75%)'  },
  { id: '#4431', name: 'Elena Rodriguez',  email: 'elena.rod@provider.com',       status: 'locked', role: 'user',  avatarBg: 'hsl(180, 30%, 75%)' },
  { id: '#2109', name: 'Robert Wilson',    email: 'r.wilson.health@outlook.com',  status: 'active', role: 'admin', avatarBg: 'hsl(90, 35%, 70%)'  },
];
const STATUS_LABEL = {
  active: 'Đang hoạt động',
  paused: 'Ngừng hoạt động',
  locked: 'Đã khoá',
};
// ---------- State ----------
const state = {
  filter: 'all',   // 'all' | 'admin' | 'selected'
  search: '',
  page: 1,
};
// ---------- Helpers ----------
const $ = (sel) => document.querySelector(sel);
function iconHTML(name) {
  // Sử dụng lucide-static font: class "icon icon-<name>"
  return `<i class="icon icon-${name}"></i>`;
}
function escapeHTML(s) {
  return String(s).replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[c]));
}
// ---------- Render sidebar ----------
function renderNav() {
  const ul = $('#nav-list');
  ul.innerHTML = NAV_ITEMS.map((item) => `
    <li>
      <a href="#" class="nav-item ${item.active ? 'is-active' : ''}">
        ${iconHTML(item.icon)}
        ${escapeHTML(item.label)}
      </a>
    </li>
  `).join('');
}
// ---------- Render bảng ----------
function getVisibleUsers() {
  let rows = USERS;
  if (state.filter === 'admin') rows = rows.filter((u) => u.role === 'admin');
  if (state.search) {
    const q = state.search.toLowerCase();
    rows = rows.filter((u) =>
      u.name.toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      u.id.toLowerCase().includes(q)
    );
  }
  return rows;
}
function renderTable() {
  const tbody = $('#user-tbody');
  const rows = getVisibleUsers();
  if (rows.length === 0) {
    tbody.innerHTML = `
      <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted-foreground)">
        Không có người dùng phù hợp.
      </td></tr>`;
  } else {
    tbody.innerHTML = rows.map((u) => `
      <tr data-id="${escapeHTML(u.id)}">
        <td class="cell-id">${escapeHTML(u.id)}</td>
        <td><div class="avatar" style="background:${u.avatarBg}"></div></td>
        <td class="cell-name">${escapeHTML(u.name)}</td>
        <td class="cell-email">${escapeHTML(u.email)}</td>
        <td>
          <span class="badge ${u.status}">
            <span class="dot"></span>
            ${STATUS_LABEL[u.status]}
          </span>
        </td>
        <td>
          <button class="btn-delete" data-action="delete" aria-label="Xoá">
            ${iconHTML('trash-2')}
          </button>
        </td>
      </tr>
    `).join('');
  }
  $('#pagination-info').textContent =
    `Hiển thị ${rows.length} trên 12.482 người dùng`;
}
function renderPagination() {
  const pages = [
    { html: iconHTML('chevron-left'),  page: Math.max(1, state.page - 1) },
    { html: '1', page: 1 },
    { html: '2', page: 2 },
    { html: '3', page: 3 },
    { html: iconHTML('chevron-right'), page: state.page + 1 },
  ];
  $('#pagination-pages').innerHTML = pages.map((p, i) => {
    const active = (i > 0 && i < 4 && Number(p.html) === state.page);
    return `<button class="page-btn ${active ? 'is-active' : ''}" data-page="${p.page}">${p.html}</button>`;
  }).join('');
}
// ---------- Events ----------
function bindEvents() {
  // Filter
  $('#filter-group').addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-filter]');
    if (!btn) return;
    state.filter = btn.dataset.filter;
    document.querySelectorAll('#filter-group .filter-btn')
      .forEach((b) => b.classList.toggle('is-active', b === btn));
    renderTable();
  });
  // Search
  $('#search-input').addEventListener('input', (e) => {
    state.search = e.target.value.trim();
    renderTable();
  });
  // Delete (event delegation)
  $('#user-tbody').addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="delete"]');
    if (!btn) return;
    const id = btn.closest('tr')?.dataset.id;
    if (!id) return;
    if (!confirm(`Xoá người dùng ${id}?`)) return;
    const idx = USERS.findIndex((u) => u.id === id);
    if (idx >= 0) USERS.splice(idx, 1);
    renderTable();
  });
  // Pagination
  $('#pagination-pages').addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-page]');
    if (!btn) return;
    const p = Number(btn.dataset.page);
    if (!Number.isFinite(p) || p < 1) return;
    state.page = p;
    renderPagination();
  });
}
document.addEventListener('DOMContentLoaded', () => {
    // 1. Logic Sidebar & Table (Code cũ của Muội)
    // ... (Để nguyên phần renderNav, renderTable, bindEvents cũ của Muội ở đây) ...
    renderNav();
    renderTable();
    renderPagination();
    bindEvents();

    // 2. Logic Modal Thêm người dùng (Sửa lại cho chuẩn)
    const modal = document.getElementById('addUserModal');
    const btnAdd = document.querySelector('.btn-primary');
    const btnClose = document.querySelector('.modal-content button[type="button"]');

    if (btnAdd && modal) {
        btnAdd.addEventListener('click', (e) => {
            e.preventDefault(); // Chỉ chặn link/button bên ngoài
            modal.style.display = 'flex';
        });
    }

    if (btnClose && modal) {
        btnClose.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // Đóng khi bấm ra ngoài vùng modal
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});