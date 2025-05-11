<?php
// تحقق من حالة الجلسة
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // بدء الجلسة فقط إذا لم تكن قد بدأت بعد
}

// بيانات الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$password = "";
$database = "timebank-db";

// إنشاء الاتصال
$conn = new mysqli($host, $user, $password, $database);

// التحقق من وجود أخطاء في الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تعيين مجموعة الأحرف لتجنب مشاكل الترميز
$conn->set_charset("utf8mb4");
?>
