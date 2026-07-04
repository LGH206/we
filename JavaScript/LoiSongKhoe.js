// Chờ giao diện HTML tải xong hoàn toàn rồi mới chạy JS
document.addEventListener("DOMContentLoaded", () => {
    
    // ==========================================================================
    // 1. LOGIC CUỘN CAROUSEL (NẾU TRÊN GIAO DIỆN CÓ NÚT MŨI TÊN)
    // ==========================================================================
    const standardRow = document.querySelector(".standard-row");
    const prevBtn = document.querySelector(".carousel-nav .nav-btn:first-child");
    const nextBtn = document.querySelector(".carousel-nav .nav-btn:last-child");

    // Chỉ chạy logic cuộn nếu trên giao diện thực sự có đầy đủ các phần tử này
    if (standardRow && prevBtn && nextBtn) {
        const getScrollAmount = () => {
            const firstCard = standardRow.querySelector(".card-standard");
            return firstCard ? firstCard.offsetWidth + 30 : 300; // Chiều rộng card + 30px gap
        };

        nextBtn.addEventListener("click", () => {
            standardRow.scrollBy({
                left: getScrollAmount(),
                behavior: "smooth"
            });
        });

        prevBtn.addEventListener("click", () => {
            standardRow.scrollBy({
                left: -getScrollAmount(),
                behavior: "smooth"
            });
        });
    }

    // ==========================================================================
    // 2. LOGIC CLICK CHUYỂN HƯỚNG CHI TIẾT 5 BÀI VIẾT
    // ==========================================================================
    const cards = document.querySelectorAll(".card-standard, .card-featured"); 

    // Mảng lưu chính xác tên file HTML của 5 bài viết tương ứng với thứ tự hiển thị
    const articleLinks = [
        "thoi-quen-song-khoe.html",      // Bài 1: 5 Thói quen đơn giản
        "thoi-quen-buoi-sang.html",     // Bài 2: 7 Thói quen buổi sáng
        "loi-song-nguoi-ban-ron.html",  // Bài 3: Lối sống cho người bận rộn
        "giam-cang-thang.html",         // Bài 4: Giảm căng thẳng
        "tac-hai-thuc-khuya.html"       // Bài 5: Tác hại thức khuya
    ];

    // Lắng nghe sự kiện click trên từng card
    cards.forEach((card, index) => {
        if (index < articleLinks.length) {
            card.style.cursor = "pointer"; // Tạo hiệu ứng bàn tay khi hover

            card.addEventListener("click", () => {
                // Chuyển hướng sang trang chi tiết nằm trong thư mục Article
                window.location.href = `Article/${articleLinks[index]}`;
            });
        }
    });

});