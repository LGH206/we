<?php 
    $page_title = "Hướng dẫn sơ cứu khẩn cấp - Đời sống sức khoẻ";
    include 'header.php'; 
?>

<main class="first-aid-page">
    <section class="fa-hero">
        <div class="fa-container">
            <h1 class="fa-hero-title">Hướng dẫn sơ cứu khẩn cấp</h1>
            <p class="fa-hero-desc">Bình tĩnh là yếu tố quan trọng nhất. Hãy tìm kiếm nhanh tình huống bạn đang<br>gặp phải để nhận hướng dẫn xử lý tức thì.</p>
            <div class="fa-search-box">
                <input type="text" id="faSearchInput" placeholder="Tìm tình huống cần sơ cứu...">
                <button id="faSearchBtn" class="fa-search-btn">Tìm kiếm</button>
            </div>
        </div>
    </section>

    <div class="fa-bg-wrapper">
        <section class="fa-situations fa-container">
            <div class="fa-section-header">
                <div>
                    <h2 class="fa-section-title">Tình huống phổ biến</h2>
                    <p class="fa-section-subtitle">Chọn tình huống để xem hướng dẫn chi tiết từng bước</p>
                </div>
                <a href="#" class="fa-view-more">Xem thêm &rarr;</a>
            </div>
            
            <div class="fa-grid-4">
                <div class="fa-card active-card" data-situation="hoc-di-vat">
                    <h3>Hóc dị vật</h3>
                    <p>Nghẹn thở, khó thở do có vật lạ trong cổ họng.</p>
                </div>
                <div class="fa-card" data-situation="cam-mau">
                    <h3>Cầm máu</h3>
                    <p>Xử lý các vết thương hở và chảy máu cấp tính.</p>
                </div>
                <div class="fa-card" data-situation="bong">
                    <h3>Bỏng</h3>
                    <p>Bỏng nhiệt, bỏng hóa chất và cách sơ cứu.</p>
                </div>
                <div class="fa-card" data-situation="ngat-xiu">
                    <h3>Ngất xỉu</h3>
                    <p>Mất ý thức tạm thời và cách sơ cứu tại chỗ.</p>
                </div>
            </div>
        </section>

        <section class="fa-detail-layout fa-container">
            <div class="fa-main-guide">
                <h2 class="fa-guide-title">Sơ cứu hóc dị vật (Nghiệm pháp Heimlich)</h2>
                
                <div class="fa-step">
                    <div class="fa-step-num">1</div>
                    <div class="fa-step-content">
                        <h3>Xác định tình trạng</h3>
                        <p>Hỏi nạn nhân "Bạn có bị nghẹn không?". Nếu họ có thể nói hoặc ho mạnh, hãy khuyến khích họ tự ho để đẩy dị vật ra. Nếu họ không thể nói, thở rít hoặc ôm cổ, hãy bắt đầu sơ cứu ngay.</p>
                        <div class="fa-img-box">
                            <img src ="Image/xacdinhtinhtrang.png" alt="Xác định tình trạng" class ="fa-step-img">
                        </div>
                    </div>
                </div>

                <div class="fa-step">
                    <div class="fa-step-num">2</div>
                    <div class="fa-step-content">
                        <h3>Vỗ lưng (5 lần)</h3>
                        <p>Đứng phía sau, hơi nghiêng nạn nhân về phía trước. Dùng gót bàn tay vỗ mạnh 5 lần vào vùng giữa hai xương bả vai của nạn nhân.</p>
                        <div class="fa-img-box">
                            <img src ="Image/volung.png" alt="Vỗ lưng" class ="fa-step-img">
                        </div>
                    </div>
                </div>

                <div class="fa-step">
                    <div class="fa-step-num">3</div>
                    <div class="fa-step-content">
                        <h3>Ép bụng (5 lần)</h3>
                        <p>Vòng hai tay ôm quanh eo nạn nhân. Đặt một nắm tay ngay trên rốn. Dùng tay kia nắm lấy và ép mạnh vào trong và hướng lên trên như muốn nhấc nạn nhân lên.</p>
                        <div class="fa-img-box">
                            <img src ="Image/epbung.png" alt="Ép bụng" class ="fa-step-img">
                        </div>
                    </div>
                </div>
            </div>

            <aside class="fa-sidebar">
                <div class="fa-warning-box">
                    <h3><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> LƯU Ý QUAN TRỌNG</h3>
                    <ul>
                        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> KHÔNG dùng tay móc họng bừa bãi vì có thể đẩy dị vật vào sâu hơn.</li>
                        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> KHÔNG cho nạn nhân uống nước khi đang bị hóc.</li>
                        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Nếu nạn nhân bất tỉnh, hãy bắt đầu CPR (hồi sức tim phổi) ngay lập tức.</li>
                    </ul>
                </div>

                <div class="fa-help-box">
                    <h4>Cần trợ giúp ngay?</h4>
                    <button id="faCallBtn" class="fa-btn-call"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> GỌI 115</button>
                    <p>Chúng tôi sẽ hướng dẫn bạn</p>
                </div>
            </aside>
        </section>

        <section class="fa-other-guides fa-container">
            <h2 class="fa-section-title" style="margin-bottom: 24px;">Các hướng dẫn khác</h2>
            <div class="fa-grid-3">
                <div class="fa-guide-card" data-situation="chan-thuong">
                    <div class="fa-guide-img" style="background-color: #0f766e;">
                        <img src="Image/chanthuong.png" alt="Chấn thương" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'">
                    </div>
                    <div class="fa-guide-info">
                        <span class="fa-cat">CHẤN THƯƠNG</span>
                        <h3>Sơ cứu gãy xương & trật khớp</h3>
                    </div>
                </div>
                <div class="fa-guide-card" data-situation="moi-truong">
                    <div class="fa-guide-img" style="background-color: #0369a1;">
                        <img src="Image/saynang.png" alt="Môi trường" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'">
                    </div>
                    <div class="fa-guide-info">
                        <span class="fa-cat">MÔI TRƯỜNG</span>
                        <h3>Say nắng và kiệt sức do nhiệt</h3>
                    </div>
                </div>
                <div class="fa-guide-card" data-situation="ngo-doc">
                    <div class="fa-guide-img" style="background-color: #1e293b;">
                        <img src="Image/ngodoc.png" alt="Ngộ độc" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'">
                    </div>
                    <div class="fa-guide-info">
                        <span class="fa-cat">NGỘ ĐỘC</span>
                        <h3>Xử lý khi uống nhầm hóa chất</h3>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<script src="JavaScript/SoCuu.js"></script>

<?php include 'footer.php'; ?>