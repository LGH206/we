<?php 
    $page_title = "Kiến Tạo Hành Trình Sống Lành Mạnh - Đời sống sức khoẻ";
    include 'header.php'; 
?>

<main>
    <section class="hero-section">
        <div class="site-wrapper hero-container">
            <div class="hero-content">
                <span class="hero-badge">Sống Khoẻ Mỗi Ngày</span>
                <h1 class="hero-title">Kiến tạo hành trình<br>sống lành mạnh</h1>
                <p class="hero-description">Cập nhật những kiến thức khoa học về dinh dưỡng, tập luyện và sức khoẻ tinh thần để bạn làm chủ cuộc sống hạnh phúc và bền bỉ hơn.</p>
                <a href="#" class="btn-explore">
                    Khám phá ngay <span class="btn-arrow">➔</span>
                </a>
            </div>
        </div>
    </section>

    <section class="topics-section">
        <div class="site-wrapper">
            <div class="topics-header">
                <div class="title-block">
                    <h2 class="section-title">Chủ đề quan tâm</h2>
                    <p class="section-subtitle">Lựa chọn chuyên mục phù hợp với mục tiêu sức khoẻ của bạn.</p>
                </div>
                <div class="carousel-nav">
                    <button class="nav-btn btn-prev">‹</button>
                    <button class="nav-btn btn-next">›</button>
                </div>
            </div>

            <div class="articles-grid">
                <div class="featured-row">
                    <article class="card-featured card-mediterranean">
                        <div class="card-featured-overlay">
                            <span class="tag-label text-green">DINH DƯỠNG</span>
                            <h3 class="card-featured-title">5 Thói Quen Đơn Giản Giúp Sống Khỏe Mỗi Ngày</h3>
                            <p class="card-featured-desc">Phân tích chuyên sâu từ WHO và Bộ Y tế về nguyên tắc ăn lành uống sạch, giảm muối đường, và vận động hợp lý để đẩy lùi 80% gánh nặng bệnh tật.</p>
                            <a href="Article/5-thoi-quen-don-gian.html" class="link-readmore">Đọc tiếp <span class="link-arrow">➔</span></a>
                        </div>
                    </article>

                    <article class="card-featured card-cardio">
                        <div class="card-featured-overlay">
                            <span class="tag-label text-lightgreen">Y HỌC LỐI SỐNG</span>
                            <h3 class="card-featured-title">7 Thói Quen Buổi Sáng Giúp Cơ Thể Tràn Đầy Năng Lượng</h3>
                            <p class="card-featured-desc">Thiết lập chuỗi hành động khoa học ngay khi thức dậy giúp tối ưu hóa các chất dẫn truyền thần kinh và duy trì sự tập trung bền vững đến cuối ngày.</p>
                            <a href="Article/7-thoi-quen-buoi-sang.html" class="link-readmore">Đọc tiếp <span class="link-arrow">➔</span></a>
                        </div>
                    </article>
                </div>

                <div class="standard-row">
                    <a href="Article/cach-xay-dung-loi-song-lanh-manh.html" class="card-standard">
                        <div class="card-image img-stress"></div>
                        <div class="card-body">
                            <span class="card-category text-teal">QUẢN LÝ THỜI GIAN</span>
                            <h4 class="card-title">Cách Xây Dựng Lối Sống Lành Mạnh Cho Người Bận Rộn</h4>
                            <p class="card-desc">Chiến lược áp dụng quy tắc "Bàn tay đầy", phương pháp sơ chế Meal-prep cuối tuần và tận dụng công nghệ để chăm sóc sức khỏe khi lịch trình dày đặc.</p>
                        </div>
                        <div class="card-footer">
                            <span class="post-time">5 phút đọc</span>
                        </div>
                    </a>

                    <a href="Article/lam-the-nao-de-giam-cang-thang.html" class="card-standard">
                        <div class="card-image img-sleep"></div>
                        <div class="card-body">
                            <span class="card-category text-olive">SỨC KHỎE TINH THẦN</span>
                            <h4 class="card-title">Làm Thế Nào Để Giảm Căng Thẳng Trong Cuộc Sống?</h4>
                            <p class="card-desc">Phương pháp làm dịu cơn stress cấp tính trong 5 phút bằng kỹ thuật thở bụng 4-7-8, dọn dẹp tâm trí Brain Dump và thiết lập ranh giới dài hạn.</p>
                        </div>
                        <div class="card-footer">
                            <span class="post-time">7 phút đọc</span>
                        </div>
                    </a>

                    <a href="Article/tac-hai-cua-viec-thuc-khuya.html" class="card-standard">
                        <div class="card-image img-superfood"></div>
                        <div class="card-body">
                            <span class="card-category text-darkgreen">CẢNH BÁO Y KHOA</span>
                            <h4 class="card-title">Những Tác Hại Của Việc Thức Khuya Thường Xuyên</h4>
                            <p class="card-desc">Hệ lụy khôn lường tàn phá chức năng não bộ, suy giảm miễn dịch và tăng nguy cơ mắc các bệnh mạn tính khi liên tục tước đoạt giấc ngủ đêm tự nhiên.</p>
                        </div>
                        <div class="card-footer">
                            <span class="post-time">4 phút đọc</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>