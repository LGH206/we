// =========================================================================
// FILE XỬ LÝ TƯƠNG TÁC TRANG SƠ CỨU (socuu.js)
// =========================================================================

document.addEventListener("DOMContentLoaded", () => {
    // 1. Kho dữ liệu nội dung sơ cứu (Đã khôi phục khung chứa hình ảnh)
    const guideData = {
        "hoc-di-vat": `
            <h2 class="fa-guide-title">Sơ cứu hóc dị vật (Nghiệm pháp Heimlich)</h2>
            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Xác định tình trạng</h3>
                    <p>Hỏi nạn nhân "Bạn có bị nghẹn không?". Nếu họ có thể nói hoặc ho mạnh, hãy khuyến khích họ tự ho để đẩy dị vật ra. Nếu họ không thể nói, thở rít hoặc ôm cổ, hãy bắt đầu sơ cứu ngay.</p>
                    <div class="fa-img-box">
                        <img src="Image/xacdinhtinhtrang.png" alt="Xác định tình trạng" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Vỗ lưng (5 lần)</h3>
                    <p>Đứng phía sau, hơi nghiêng nạn nhân về phía trước. Dùng gót bàn tay vỗ mạnh 5 lần vào vùng giữa hai xương bả vai của nạn nhân.</p>
                    <div class="fa-img-box">
                        <img src="Image/volung.png" alt="Vỗ lưng" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Ép bụng (5 lần)</h3>
                    <p>Vòng hai tay ôm quanh eo nạn nhân. Đặt một nắm tay ngay trên rốn. Dùng tay kia nắm lấy và ép mạnh vào trong và hướng lên trên như muốn nhấc nạn nhân lên.</p>
                    <div class="fa-img-box">
                        <img src="Image/epbung.png" alt="Ép bụng" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        `,
        "cam-mau": `
            <h2 class="fa-guide-title">Sơ cứu vết thương chảy máu cấp tính</h2>
            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Rửa sạch và đánh giá</h3>
                    <p>Nhanh chóng dùng nước sạch hoặc nước muối sinh lý rửa nhẹ vết thương. Đánh giá xem có dị vật cắm sâu vào không (Tuyệt đối không rút dị vật lớn ra).</p>
                    <div class="fa-img-box">
                        <img src="Image/ruavetthuongbangnuoc.png" alt="Rửa vết thương" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Ép chặt để cầm máu</h3>
                    <p>Dùng miếng gạc, băng y tế hoặc vải sạch ép chặt trực tiếp lên vết thương trong ít nhất 5-10 phút liên tục. Không mở ra xem liên tục vì sẽ làm vỡ cục máu đông.</p>
                    <div class="fa-img-box">
                        <img src="Image/cammau.png" alt="Ép chặt vết thương" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Băng bó và nâng cao</h3>
                    <p>Cố định miếng gạc bằng băng cuộn. Nếu vết thương ở tay hoặc chân, hãy nâng vùng bị thương lên cao hơn tim để giảm áp lực máu dồn về đó.</p>
                    <div class="fa-img-box">
                        <img src="Image/bangbo.png" alt="Nâng cao vết thương" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        `,
        "bong": `
            <h2 class="fa-guide-title">Sơ cứu khi bị bỏng (Nhiệt/Hóa chất)</h2>
            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Làm mát vết bỏng ngay lập tức</h3>
                    <p>Xả trực tiếp vùng bị bỏng dưới vòi nước máy (nước mát bình thường) từ 15 - 20 phút. <strong>TUYỆT ĐỐI KHÔNG</strong> dùng đá lạnh, kem đánh răng hay nước mắm bôi lên.</p>
                    <div class="fa-img-box">
                        <img src="Image/lammatvetbong.png" alt="Làm mát vết bỏng" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Tháo bỏ trang sức, quần áo chật</h3>
                    <p>Nhanh chóng tháo nhẫn, đồng hồ, thắt lưng hoặc cắt bỏ quần áo quanh vùng bị bỏng trước khi vết thương sưng nề. Nếu quần áo dính chặt vào da, KHÔNG cố kéo ra.</p>
                    <div class="fa-img-box">
                        <img src="Image/vetbong.png" alt="Tháo bỏ trang sức" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Bảo vệ vết bỏng</h3>
                    <p>Dùng gạc vô trùng hoặc vải mỏng sạch, không có xơ tơ che phủ nhẹ nhàng lên vết bỏng để tránh nhiễm trùng. Không chọc vỡ các bóng nước.</p>
                    <div class="fa-img-box">
                        <img src="Image/chevetbong.png" alt="Bảo vệ vết bỏng" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        `,
        "ngat-xiu": `
            <h2 class="fa-guide-title">Xử lý khi có người ngất xỉu</h2>
            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Đặt nạn nhân nằm xuống</h3>
                    <p>Từ từ đỡ nạn nhân nằm ngửa xuống mặt phẳng an toàn. Kê hai chân nạn nhân lên cao hơn tim (khoảng 30cm) để máu lưu thông về não dễ dàng hơn.</p>
                    <div class="fa-img-box">
                        <img src="Image/ngatxiu.png" alt="Đặt nạn nhân nằm" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Nới lỏng quần áo và tạo không gian thoáng</h3>
                    <p>Nới lỏng cổ cồn, thắt lưng, cúc áo. Yêu cầu đám đông lùi lại để nạn nhân có đủ oxy để thở. Quạt mát nhẹ nhàng cho họ.</p>
                    <div class="fa-img-box">
                        <img src="Image/vetbong.png" alt="Nới lỏng quần áo" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Theo dõi nhịp thở và gọi cấp cứu</h3>
                    <p>Kiểm tra xem nạn nhân còn thở không. Thường nạn nhân sẽ tỉnh lại sau 1-2 phút. Nếu sau 3 phút không tỉnh, hoặc ngất kèm theo co giật, hãy gọi 115 ngay.</p>
                    <div class="fa-img-box">
                        <img src="Image/namnghieng.png" alt="Gọi cấp cứu" class="fa-step-img" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        `,
        
        "chan-thuong": `
            <h2 class="fa-guide-title">Sơ cứu gãy xương & trật khớp</h2>

            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Cố định vùng tổn thương</h3>
                    <p>Không di chuyển mạnh vùng nghi ngờ gãy xương hoặc trật khớp. Dùng nẹp hoặc vật cứng để cố định.</p>
                    <div class="fa-img-box">
                        <img src="Image/chanthuong1.png" alt="Chấn thương" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Chườm lạnh</h3>
                    <p>Chườm lạnh từ 15-20 phút để giảm đau và hạn chế sưng.</p>
                    <div class="fa-img-box">
                        <img src="Image/chanthuong2.png" alt="Chấn thương" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Đưa đến cơ sở y tế</h3>
                    <p>Không tự ý nắn chỉnh xương. Nhanh chóng đưa nạn nhân đến bệnh viện.</p>
                    <div class="fa-img-box">
                        <img src="Image/chanthuong3.png" alt="Chấn thương" class="fa-step-img">
                    </div>
                </div>
            </div>
        `,

        "moi-truong": `
            <h2 class="fa-guide-title">Say nắng và kiệt sức do nhiệt</h2>

            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Đưa vào nơi mát</h3>
                    <p>Nhanh chóng đưa nạn nhân đến nơi thoáng mát, tránh ánh nắng trực tiếp.</p>
                    <div class="fa-img-box">
                        <img src="Image/saynang1.png" alt="Say nắng" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Làm mát cơ thể</h3>
                    <p>Nới lỏng quần áo và làm mát bằng khăn ướt hoặc quạt.</p>
                    <div class="fa-img-box">
                        <img src="Image/saynang2.png" alt="Say nắng" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Bù nước</h3>
                    <p>Nếu còn tỉnh táo, cho nạn nhân uống nước từng ngụm nhỏ.</p>
                    <div class="fa-img-box">
                        <img src="Image/saynang3.png" alt="Say nắng" class="fa-step-img">
                    </div>
                </div>
            </div>
        `,

        "ngo-doc": `
            <h2 class="fa-guide-title">Xử lý khi uống nhầm hóa chất</h2>

            <div class="fa-step">
                <div class="fa-step-num">1</div>
                <div class="fa-step-content">
                    <h3>Xác định loại hóa chất</h3>
                    <p>Giữ lại bao bì hoặc nhãn sản phẩm để cung cấp cho nhân viên y tế.</p>
                    <div class="fa-img-box">
                        <img src="Image/ngodoc1.png" alt="Ngộ độc" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">2</div>
                <div class="fa-step-content">
                    <h3>Không tự ý gây nôn</h3>
                    <p>Không gây nôn nếu chưa có hướng dẫn của nhân viên y tế.</p>
                    <div class="fa-img-box">
                        <img src="Image/ngodoc2.png" alt="Ngộ độc" class="fa-step-img">
                    </div>
                </div>
            </div>

            <div class="fa-step">
                <div class="fa-step-num">3</div>
                <div class="fa-step-content">
                    <h3>Gọi cấp cứu</h3>
                    <p>Liên hệ 115 hoặc đưa nạn nhân tới cơ sở y tế gần nhất.</p>
                    <div class="fa-img-box">
                        <img src="Image/ngodoc3.png" alt="Ngộ độc" class="fa-step-img">
                    </div>
                </div>
            </div>
        `
 
    };

    // 2. Lắng nghe sự kiện click vào các ô Tình huống (Tabs)
    const cards = document.querySelectorAll(".fa-card");
    const mainGuide = document.querySelector(".fa-main-guide");
    const otherGuideCards = document.querySelectorAll(".fa-guide-card");

    cards.forEach(card => {
        card.addEventListener("click", () => {
            // Xóa class 'active-card' ở tất cả các ô
            cards.forEach(c => c.classList.remove("active-card"));

            // Thêm class 'active-card' cho ô vừa click
            card.classList.add("active-card");

            // Lấy ID tình huống từ thuộc tính data-situation
            const situationKey = card.getAttribute("data-situation");

            // Cập nhật giao diện bên dưới bằng data tương ứng, kèm hiệu ứng mờ ảo (fade-in)
            if (guideData[situationKey]) {
                mainGuide.style.opacity = 0; // ẩn đi
                setTimeout(() => {
                    mainGuide.innerHTML = guideData[situationKey];
                    mainGuide.style.opacity = 1; // hiện ra lại
                    mainGuide.style.transition = "opacity 0.3s ease-in-out";
                }, 150);
            }
        });
    });
    
    otherGuideCards.forEach(card => {
        card.addEventListener("click", () => {
            const situationKey = card.getAttribute("data-situation");

            if (guideData[situationKey]) {
                mainGuide.style.opacity = 0;

                setTimeout(() => {
                    mainGuide.innerHTML = guideData[situationKey];
                    mainGuide.style.opacity = 1;
                    mainGuide.style.transition = "opacity 0.3s ease-in-out";

                    document.querySelector(".fa-detail-layout")
                        .scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });
                }, 150);
            }
        });
    });


    // 3. Nút GỌI 115 (Mở trình gọi điện của máy tính/điện thoại)
    const callBtn = document.getElementById("faCallBtn");
    if (callBtn) {
        callBtn.addEventListener("click", () => {
            window.location.href = "tel:115";
        });
    }
    
    // 4. Tính năng Tìm kiếm nhanh
    const searchInput = document.getElementById("faSearchInput");
    const searchBtn = document.getElementById("faSearchBtn");

    const performSearch = () => {
        const keyword = searchInput.value.toLowerCase().trim();

        if (!keyword) return;

        let found = false;

        // Tìm trong các card phía trên
        cards.forEach(card => {
            const title = card.querySelector("h3").innerText.toLowerCase();

            if (title.includes(keyword)) {
                card.click();

                card.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                found = true;
            }
        });

        // Tìm trong các card phía dưới
        otherGuideCards.forEach(card => {
            const title = card.querySelector("h3").innerText.toLowerCase() + " " + card.querySelector(".fa-cat").innerText.toLowerCase();

            if (title.includes(keyword)) {
                card.click();

                card.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                found = true;
            }
        });

        if (!found) {
            alert(
                "Chưa tìm thấy hướng dẫn cho: " +
                keyword +
                ". Vui lòng thử: hóc dị vật, cầm máu, bỏng, ngất xỉu, chấn thương, say nắng hoặc ngộ độc."
            );
        }
    };

    searchBtn?.addEventListener("click", performSearch);

    searchInput?.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            performSearch();
        }
    });

    // // 4. Tính năng Tìm kiếm nhanh
    // const searchInput = document.getElementById("faSearchInput");
    // const searchBtn = document.getElementById("faSearchBtn");
    
    // const performSearch = () => {
    //     const keyword = searchInput.value.toLowerCase().trim();
    //     if (!keyword) return;

    //     let found = false;
    //     cards.forEach(card => {
    //         const title = card.querySelector("h3").innerText.toLowerCase();
    //         if (title.includes(keyword)) {
    //             card.click(); // Tự động click vào thẻ tìm thấy
    //             card.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Cuộn màn hình tới đó
    //             found = true;
    //         }
    //     });

    //     if (!found) {
    //         alert("Chưa tìm thấy hướng dẫn cho: " + keyword + ". Vui lòng thử từ khóa khác (VD: cầm máu, bỏng...)");
    //     }
    // };

    // searchBtn?.addEventListener("click", performSearch);
    // searchInput?.addEventListener("keypress", (e) => {
    //     if (e.key === "Enter") performSearch();
    // });
});