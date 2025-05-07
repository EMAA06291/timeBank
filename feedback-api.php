<?php
require 'config.php';
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized: User not logged in"]);
    http_response_code(401);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    
    $required_fields = ['message', 'rating'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["error" => "حقل '$field' مطلوب"]);
            http_response_code(400);
            exit;
        }
    }

    
    if ($data['rating'] < 1 || $data['rating'] > 5) {
        echo json_encode(["error" => "التقييم يجب أن يكون بين 1 و 5"]);
        http_response_code(400);
        exit;
    }

    
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, message, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $data['message'], $data['rating']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "شكرًا لتقييمك! تم حفظ التعليقات بنجاح"]);
        http_response_code(201);
    } else {
        echo json_encode(["error" => "حدث خطأ أثناء الحفظ: " . $stmt->error]);
        http_response_code(500);
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    $stmt->execute();
    $result = $stmt->get_result();
    $feedbacks = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode($feedbacks);
    exit;
}


echo json_encode(["error" => "مسار غير صحيح"]);
http_response_code(404);
?>