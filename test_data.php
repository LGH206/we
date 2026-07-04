<?php
/**
 * Script PHP nạp dữ liệu mẫu bình luận (khen, chê, chửi, tốt, xấu)
 * Bảng mục tiêu: comments (id, post_id, user_id, content, status, created_at, updated_at)
 * ĐÃ TỐI ƯU: Sử dụng một post_id cố định thay vì sinh ngẫu nhiên, tự động lấy user_id hợp lệ từ DB
 */

// 1. Cấu hình thông tin kết nối Database
$host = 'localhost';
$db   = 'suckhoe'; 
$user = 'root';                
$pass = '';                    

// THIẾT LẬP: ID bài viết muốn nạp bình luận
$targetPostId = 2; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "Kết nối database thành công!\n";

    // 2. Kiểm tra xem bài viết mục tiêu có tồn tại trong CSDL không (Tránh lỗi khóa ngoại)
    $stmtCheckPost = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $stmtCheckPost->execute([$targetPostId]);
    if (!$stmtCheckPost->fetch()) {
        die("Lỗi: Bài viết có ID = $targetPostId không tồn tại trong bảng 'posts'. Vui lòng kiểm tra lại ID bài viết của bạn!\n");
    }
    echo "Xác nhận: Bài viết mục tiêu ID = $targetPostId tồn tại hợp lệ.\n";

    // 3. Lấy danh sách user_id đang thực sự tồn tại trong CSDL để liên kết bình luận
    $stmtUsers = $pdo->query("SELECT id FROM users WHERE id >= 2");
    $userIds = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

    if (empty($userIds)) {
        // Nếu không có người dùng nào có ID >= 2, lấy tất cả người dùng hiện có
        $userIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
    }

    if (empty($userIds)) {
        die("Lỗi: Bảng 'users' của bạn đang trống. Hãy tạo người dùng trước khi nạp bình luận!\n");
    }
    echo "Tìm thấy " . count($userIds) . " người dùng hợp lệ để liên kết.\n";

    // 4. Danh sách các bình luận mẫu được phân loại rõ ràng (khen, chê, chửi, tốt, xấu)
    $commentsPool = [
        // NHÓM 1: KHEN / TỐT (Trạng thái: approved - Đã duyệt)
        [
            'type' => 'khen_tot',
            'status' => 'approved',
            'contents' => [
                'Bài viết vô cùng hữu ích, cảm ơn bác sĩ nhiều!',
                'Thông tin rất thực tế và dễ áp dụng hàng ngày.',
                'Tôi đã thử làm theo hướng dẫn ăn uống này và thấy người khỏe hẳn ra.',
                'Tuyệt vời! Mong trang web cập nhật thêm nhiều bài viết khoa học thế này.',
                'Cách trình bày bài viết rất rõ ràng, hình ảnh minh họa đẹp mắt.',
                'Đọc xong bài viết này tôi đã hiểu rõ hơn về bệnh tình của mình, cảm ơn đội ngũ biên tập.',
                'Kiến thức chuyên môn rất sâu sắc, chia sẻ rất có tâm.',
                'Rất đồng tình với quan điểm của tác giả. 10 điểm cho chất lượng!',
                'Giao diện trang web đẹp, nội dung bài viết lại chất lượng nữa.',
                'Phương pháp phòng ngừa này đơn giản mà hiệu quả quá.'
            ]
        ],
        // NHÓM 2: CHÊ (Trạng thái: pending - Chờ duyệt)
        [
            'type' => 'che',
            'status' => 'pending',
            'contents' => [
                'Bài viết viết hơi sơ sài, chưa đi sâu vào chi tiết phác đồ điều trị.',
                'Hình ảnh minh họa hơi mờ và khó nhìn quá tác giả ơi.',
                'Thông tin này tôi thấy trên mạng nhiều rồi, không có gì mới mẻ cả.',
                'Bài viết hơi dài dòng, nên tóm tắt lại các ý chính thì tốt hơn.',
                'Nội dung khuyên ăn gạo lứt nhưng không nói rõ người bị thận có ăn được không.',
                'Khuyên vận động nhưng không đưa ra thời gian tập cụ thể cho từng độ tuổi.',
                'Có một số chỗ dùng thuật ngữ chuyên ngành khó hiểu quá.',
                'Tôi thấy phương pháp này không thực tế lắm với người bận rộn.',
                'Nên có thêm video hướng dẫn trực quan thì bài viết sẽ hoàn hảo hơn.'
            ]
        ],
        // NHÓM 3: CHỬI / XẤU / SPAM (Trạng thái: spam - Ẩn/Chặn)
        [
            'type' => 'chui_xau',
            'status' => 'spam',
            'contents' => [
                'Viết ngu như bò, chả biết tí gì về y học mà cũng bày đặt viết bài khuyên răn!',
                'Bọn lừa đảo! Đừng ai tin theo cái phương pháp vớ vẩn này, tí nữa thì nhập viện.',
                'Đm viết bài câu view rẻ tiền, thông tin sai lệch hoàn toàn.',
                'Nhà cái uy tín hàng đầu Châu Á, nạp rút 30s liên hệ ngay Zalo 09xxxxxx!',
                'Bán thuốc đặc trị trĩ dứt điểm hoàn toàn sau 7 ngày, không hiệu quả hoàn tiền!',
                'Mẹ kiếp, làm theo hướng dẫn ăn uống này xong bị đau bụng tiêu chảy cả đêm.',
                'Cút đi đồ lừa đảo, chia sẻ kiến thức rác rưởi hại người.',
                'Nhận hack tài khoản Facebook, định vị số điện thoại giá rẻ cam kết uy tín...',
                'Xem bói tử vi miễn phí, inbox ngay zalo thầy để được giải hạn!'
            ]
        ]
    ];

    // 5. Chuẩn bị câu lệnh SQL chèn dữ liệu
    $sql = "INSERT INTO comments (post_id, user_id, content, status, created_at, updated_at) 
            VALUES (:post_id, :user_id, :content, :status, :created_at, :updated_at)";
    $stmt = $pdo->prepare($sql);

    $successCount = 0;
    $pdo->beginTransaction(); // Khởi động transaction để chạy nhanh và an toàn

    // Sinh ngẫu nhiên khoảng 100 bình luận
    for ($i = 0; $i < 100; $i++) {
        $group = $commentsPool[array_rand($commentsPool)];
        $content = $group['contents'][array_rand($group['contents'])];
        $status = $group['status'];

        // Sử dụng targetPostId cố định và lấy ngẫu nhiên user_id thực tế
        $postId = $targetPostId;
        $userId = $userIds[array_rand($userIds)];

        // Sinh thời gian ngẫu nhiên trong vòng 15 ngày qua
        $daysAgo = rand(0, 15);
        $createdAt = date('Y-m-d H:i:s', strtotime("-$daysAgo days -" . rand(0, 23) . " hours"));
        $updatedAt = $createdAt;

        $stmt->execute([
            ':post_id'    => $postId,
            ':user_id'    => $userId,
            ':content'    => $content,
            ':status'     => $status,
            ':created_at' => $createdAt,
            ':updated_at' => $updatedAt
        ]);
        $successCount++;
    }

    $pdo->commit();
    echo "Đã nạp thành công $successCount bình luận mẫu (Khen, Chê, Chửi) vào bài viết ID = $targetPostId!\n";

} catch (\PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Lỗi: " . $e->getMessage() . "\n";
}