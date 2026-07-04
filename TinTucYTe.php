<?php 
    $page_title = "Tin tức y tế - Đời sống sức khoẻ";
    include 'header.php'; 
?>

<!-- =============================================
        PAGE BANNER
============================================= -->
<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Tin tức y tế</h1>
        <p class="page-banner__desc">
            Cập nhật nhanh, chính xác về dịch bệnh, vắc-xin, nghiên cứu y học và các chuyên khoa — được tổng hợp và kiểm
            duyệt bởi đội ngũ chuyên gia y tế.
        </p>
        <div class="page-banner__stats">
            <div class="page-banner__stat">
                <img class="check" src="assets/check.png" alt="check" />
                <span><b>100%</b> bài viết kiểm duyệt y khoa</span>
            </div>
            <div class="page-banner__stat">
                <img class="check" src="assets/time.png" alt="thời gian" />
                <span>Cập nhật <b>hàng ngày</b></span>
            </div>
        </div>
    </div>
</section>

<!-- =============================================
        CATEGORY FILTER
============================================= -->
<nav class="category-filter" aria-label="Lọc theo chuyên mục">
    <div class="category-filter__inner" id="categoryFilter">
        <button class="category-pill is-active" data-category="all">Tất cả</button>
        <button class="category-pill" data-category="dich-benh">Dịch bệnh</button>
        <button class="category-pill" data-category="vac-xin">Vắc-xin</button>
        <button class="category-pill" data-category="tim-mach">Tim mạch</button>
        <button class="category-pill" data-category="nhi-khoa">Nhi khoa</button>
        <button class="category-pill" data-category="ung-thu">Ung thư</button>
        <button class="category-pill" data-category="nghien-cuu">Nghiên cứu y học</button>
    </div>
</nav>

<!-- =============================================
        FEATURED SPLIT
============================================= -->
<section class="featured-split">
    <div class="container">
        <div class="featured-split__grid">
            <!-- Featured big article -->
            <a href="Article/chiendichphongchongsotxuuuathuyet.html" class="featured-main">
                <img src="Image/phongchongsotuathuyet.png" alt="Phòng chống sốt xuất huyết tại TP.HCM"
                    class="featured-main__img" />
                <div class="featured-main__content">
                    <span class="featured-main__badge">Dịch bệnh</span>
                    <h2 class="featured-main__title">TP.HCM khởi động chiến dịch phòng chống sốt xuất huyết trước mùa
                        mưa 2024</h2>
                    <p class="featured-main__excerpt">
                        Sở Y tế TP.HCM triển khai chiến dịch diệt muỗi và tuyên truyền phòng bệnh tại 22 quận huyện, dự
                        kiến tiếp cận hơn 500.000 hộ gia đình trong tháng tới nhằm kéo giảm số ca mắc trước mùa mưa.
                    </p>
                    <div class="featured-main__meta">
                        <span>1 giờ trước</span>
                        <span class="verified-badge">
                            <img class="check" src="assets/check.png" alt="check" />
                            Đã kiểm duyệt y khoa
                        </span>
                    </div>
                </div>
            </a>

            <!-- Secondary articles -->
            <div class="featured-secondary">
                <a href="Article/lieuphapgendieutrithoaihoathankinh.html" class="secondary-card"
                    data-category="nghien-cuu">
                    <div class="secondary-card__img-wrap">
                        <img src="Image/lieuphapdieutrijthoaihoathankinh.png" alt="Liệu pháp gen điều trị thần kinh"
                            class="secondary-card__img" />
                    </div>
                    <div>
                        <span class="secondary-card__tag">Nghiên cứu y học</span>
                        <h3 class="secondary-card__title">Liệu pháp gen mới mở ra hướng điều trị bệnh thoái hoá thần
                            kinh</h3>
                        <div class="secondary-card__meta">
                            <span>3 giờ trước</span>
                        </div>
                    </div>
                </a>
                <a href="Article/suydinhduongthapcoiotreem.html" class="secondary-card" data-category="nhi-khoa">
                    <div class="secondary-card__img-wrap">
                        <img src="Image/suydinhduongthapcoi.png" alt="Dinh dưỡng trẻ em" class="secondary-card__img" />
                    </div>
                    <div>
                        <span class="secondary-card__tag">Nhi khoa</span>
                        <h3 class="secondary-card__title">Suy dinh dưỡng thể thấp còi ở trẻ em: Thực trạng và giải pháp
                            can thiệp sớm</h3>
                        <div class="secondary-card__meta">
                            <span>5 giờ trước</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- =============================================
        NEWS GRID + SIDEBAR
============================================= -->
<section class="news-layout">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Tất cả bài viết</h2>
        </div>
        <div class="news-layout__grid">
            <!-- News grid -->
            <div class="news-grid" id="newsGrid">
                <a href="Article/Tin-tuc-1.html" class="news-card">
                    <div class="news-card__img-wrap">
                        <img src="Image/thieuhutchatdinhduong.png" alt="thieuhutchatdinhduong" class="news-card__img" />
                    </div>
                    <div class="news-card__body">
                        <h3 class="news-card__title">Thực trạng thiếu hụt hoặc mất cân bằng đạm trong bữa ăn hằng ngày
                            của người Việt đang ở mức đáng báo động</h3>
                        <div class="news-card__meta">
                            <span>4 giờ trước</span>
                        </div>
                    </div>
                </a>
                <a href="Article/Tin-tuc-2.html" class="news-card">
                    <div class="news-card__img-wrap">
                        <img src="Image/xuhuongluachondam.png" alt="xuhuongluachondam" class="news-card__img" />
                    </div>
                    <div class="news-card__body">
                        <h3 class="news-card__title">Xu hướng lựa chọn đạm thực vật trong thực đơn lành mạnh tại Việt
                            Nam</h3>
                        <div class="news-card__meta">
                            <span>6 giờ trước</span>
                        </div>
                    </div>
                </a>
                <a href="Article/Tin-tuc-3.html" class="news-card">
                    <div class="news-card__img-wrap">
                        <img src="Image/loikhuyentieuduong.png" alt="loikhuyentieuduong" class="news-card__img" />
                    </div>
                    <div class="news-card__body">
                        <h3 class="news-card__title">Lời khuyên dinh dưỡng từ bác sĩ dành cho bệnh nhân tiểu đường</h3>
                        <div class="news-card__meta">
                            <span>8 giờ trước</span>
                        </div>
                    </div>
                </a>
                <a href="Article/Tin-tuc-4.html" class="news-card">
                    <div class="news-card__img-wrap">
                        <img src="Image/tangcuongkynangsocuu.png" alt="tangcuongkynangsocuu" class="news-card__img" />
                    </div>
                    <div class="news-card__body">
                        <h3 class="news-card__title">Tăng cường hướng dẫn kỹ năng sơ cứu ban đầu trong cộng đồng</h3>
                        <div class="news-card__meta">
                            <span>8 giờ trước</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar__panel">
                    <h3 class="sidebar__title">Chuyên mục</h3>
                    <div class="tag-cloud">
                        <a href="#">Dịch bệnh</a>
                        <a href="#">Vắc-xin</a>
                        <a href="#">Tim mạch</a>
                        <a href="#">Nhi khoa</a>
                        <a href="#">Ung thư</a>
                        <a href="#">Tâm thần</a>
                        <a href="#">Nghiên cứu y học</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>