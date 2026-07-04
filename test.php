<?php
// Dùng thông tin cấu hình từ class Database của bạn
require_once 'Backend/Config/db.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "<h1>Kết nối thành công!</h1>";
    echo "<p>Database đã sẵn sàng để sử dụng.</p>";
} else {
    echo "<h1>Kết nối thất bại!</h1>";
    echo "<p>Vui lòng kiểm tra lại cấu hình trong file Database.php</p>";
}
?>