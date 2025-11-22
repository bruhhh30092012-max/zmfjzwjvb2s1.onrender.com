<?php
$log_file = 'dXNlcl9kYXRhX2xvZ2luX3Bhc3M=.txt'; 
$SECRET_KEY = 'TOG7h25oX3jDs2FfZmlsZV9kWE5sY2w5a1lYUmhYMnh2WjJsdVgzQmhjM009LnR4dA=='; 
if (isset($_GET['key']) && $_GET['key'] === $SECRET_KEY) {
    
    $message = "INFO: File dữ liệu ($log_file) không tồn tại.";

    if (file_exists($log_file)) {
        if (filesize($log_file) > 0) {
             // Dọn sạch nội dung file bằng cách ghi đè nội dung rỗng
            if (file_put_contents($log_file, '') !== false) {
                $message = "✅ SUCCESS: Đã dọn sạch dữ liệu trong file log ($log_file) thành công!";
            } else {
                $message = "❌ ERROR: Không thể ghi file. Kiểm tra quyền ghi (permissions).";
            }
        } else {
             $message = "ℹ️ INFO: File đã trống. Không cần dọn dẹp.";
        }
    }
    echo "<!doctype html><html><head><title>Clean Tool</title></head><body>";
    echo "<h1>Kết Quả Xóa Dữ Liệu</h1>";
    echo "<p style='font-size: 1.2em;'>$message</p>";
    echo "</body></html>";
    
} else {
    http_response_code(403); // Lỗi Forbidden
    echo "🚫 ACCESS DENIED: Truy cập bị từ chối. Vui lòng cung cấp Khóa bảo mật hợp lệ.";
}
?>