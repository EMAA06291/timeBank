<?php
// تضمين ملف الإعدادات
require 'config.php';

// تعيين رأس الصفحة لتحديد نوع المحتوى
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// التحقق من نوع الطلب (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // استعلام لاسترجاع جميع الخدمات من قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM services");
    $stmt->execute();
    $result = $stmt->get_result();
    
    // تحويل النتيجة إلى مصفوفة
    $services = $result->fetch_all(MYSQLI_ASSOC);

    // إرجاع النتيجة بتنسيق JSON
    echo json_encode($services);
    exit;
}

// إذا كان نوع الطلب غير صحيح
echo json_encode(["error" => "Invalid request"]);
http_response_code(405);
?>
