<?php 
    $page_title = "Tình trạng sức khoẻ - Đời sống sức khoẻ";
    include 'header.php'; 
?>

<!-- =====================================================
    HERO TÌM KIẾM CHUYÊN MỤC
===================================================== -->
<section class="category-hero-section">
    <div class="site-wrapper">
    <h1 class="category-hero-title">Khám phá chuyên mục sức khoẻ</h1>
    <p class="category-hero-subtitle">
        Tìm kiếm thông tin y khoa tin cậy từ đội ngũ chuyên gia hàng đầu để bảo vệ sức khoẻ gia đình bạn.
    </p>

    <form class="category-search-bar" role="search" onsubmit="return false;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input
        type="text"
        class="category-search-input"
        placeholder="Tìm kiếm chuyên mục..."
        aria-label="Tìm kiếm chuyên mục sức khoẻ"
        />
    </form>
    </div>
</section>

<!-- =====================================================
    GRID DANH MỤC "BỆNH LÝ"
===================================================== -->
<section class="category-grid-section">
    <div class="site-wrapper">
        <h2 class="category-grid-heading">Bệnh lý</h2>
        <div class="category-card-grid">

            <!-- Tim mạch & Tuần hoàn -->
            <a href="Benhly/TimMachTuanHoan.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/timmach.png" alt="timmaach"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Tim mạch &amp; Tuần hoàn</h3>
                <p class="category-card-desc">Thông tin về cao huyết áp, đột quỵ và sức khoẻ tim mạch cơ bản.</p>
            </div>
            </a>

            <!-- Hô hấp -->
            <a href="Benhly/HoHap.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/hohap.png" alt="hohap">
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Hô hấp</h3>
                <p class="category-card-desc">Thông tin về hen suyễn, viêm phổi và các bệnh đường hô hấp thường gặp.</p>
            </div>
            </a>

            <!-- Tiêu hóa -->
            <a href="Benhly/TieuHoa.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/tieuhoa.png" alt="tieuhoa"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Tiêu hóa</h3>
                <p class="category-card-desc">Thông tin về dạ dày, đại tràng và duy trì hệ thống tiêu hóa khoẻ mạnh.</p>
            </div>
            </a>

            <!-- Cơ xương khớp -->
            <a href="Benhly/CoXuongKhop.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/coxuongkhop.png" alt="coxuongkhop"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Cơ xương khớp</h3>
                <p class="category-card-desc">Giải pháp cho thoái hóa khớp, loãng xương và đau mỏi vai gáy hằng ngày.</p>
            </div>
            </a>

            <!-- Thần kinh -->
            <a href="Benhly/ThanKinh.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/thankinh.png" alt="thankinh"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Thần kinh</h3>
                <p class="category-card-desc">Điều trị đau đầu, mất ngủ và các rối loạn chức năng thần kinh phổ biến.</p>
            </div>
            </a>

            <!-- Nội tiết & Chuyển hóa -->
            <a href="Benhly/NoiTietChuyenHoa.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/noitietchuyenhoa.png" alt="noitietchuyenhoa"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Nội tiết &amp; Chuyển hóa</h3>
                <p class="category-card-desc">Quản lý tiểu đường, bệnh lý tuyến giáp và rối loạn chuyển hóa mạn tính.</p>
            </div>
            </a>

            <!-- Thận & Tiết niệu -->
            <a href="Benhly/ThanTietNieu.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/thantietnieu.png" alt="thantietnieu"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Thận &amp; Tiết niệu</h3>
                <p class="category-card-desc">Sức khoẻ thận, bàng quang và các vấn đề về đường tiết niệu ở mọi độ tuổi.</p>
            </div>
            </a>

            <!-- Sinh dục & Sinh sản -->
            <a href="Benhly/SinhDucSinhSan.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/sinhducsinhsan.png" alt="sinhducsinhsan"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Sinh dục &amp; Sinh sản</h3>
                <p class="category-card-desc">Chăm sóc sức khoẻ sinh sản định kỳ và kế hoạch hóa gia đình an toàn.</p>
            </div>
            </a>

            <!-- Cơ quan giác quan -->
            <a href="Benhly/CoQuanGiacQuan.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/coquangiacquan.png" alt="coquangiacquan"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Cơ quan giác quan</h3>
                <p class="category-card-desc">Bảo vệ thị lực, thính lực và sức khoẻ các cơ quan giác quan trong cuộc sống.</p>
            </div>
            </a>

            <!-- Ngoài da -->
            <a href="Benhly/NgoaiDa.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/ngoaida.png" alt="ngoaida"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Ngoài da</h3>
                <p class="category-card-desc">Thông tin về các bệnh lý về da thường gặp, cách chăm sóc và điều trị hiệu quả.</p>
            </div>
            </a>

            <!-- Trẻ em -->
            <a href="Benhly/TreEm.php" class="category-card">
            <div class="category-card-icon" aria-hidden="true">
                <img class="icon" src="assets/treem.png" alt="treem"/>
            </div>
            <div class="category-card-body">
                <h3 class="category-card-title">Trẻ em</h3>
                <p class="category-card-desc">Thông tin về các bệnh lý ở trẻ em thường gặp, cách chăm sóc và điều trị hiệu quả.</p>
            </div>
            </a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>