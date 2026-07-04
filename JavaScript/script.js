/* =========================================================
   Admin Profile — interactions
   ========================================================= */

// 1) Toggle hiển thị mật khẩu
document.querySelectorAll(".toggle-pwd").forEach((btn) => {
  btn.addEventListener("click", () => {
    const input = btn.parentElement.querySelector("input[data-pwd]");
    const icon = btn.querySelector("i");
    if (!input) return;
    const isHidden = input.type === "password";
    input.type = isHidden ? "text" : "password";
    icon.className = isHidden ? "icon-eye-off" : "icon-eye";
  });
});

// 2) Đo độ mạnh mật khẩu mới
const newPwd = document.getElementById("newPwd");
const fill = document.getElementById("strengthFill");
const label = document.getElementById("strengthLabel");
const LABELS = ["Too short", "Weak", "Fair", "Good", "Strong password"];

function scorePassword(pwd) {
  if (!pwd) return 0;
  let score = 0;
  if (pwd.length >= 8) score++;
  if (/[A-Z]/.test(pwd) && /[a-z]/.test(pwd)) score++;
  if (/\d/.test(pwd)) score++;
  if (/[^A-Za-z0-9]/.test(pwd)) score++;
  return Math.min(score, 4);
}

if (newPwd) {
  newPwd.addEventListener("input", (e) => {
    const score = scorePassword(e.target.value);
    fill.style.width = (score / 4) * 100 + "%";
    label.textContent = LABELS[score];
  });
}

// 3) Submit demo
document.getElementById("saveProfile")?.addEventListener("click", () => {
  alert("Đã lưu thông tin profile.");
});
document.getElementById("updatePwd")?.addEventListener("click", () => {
  alert("Đã cập nhật mật khẩu.");
});
