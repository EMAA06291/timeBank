<?php
require 'db.php'; 
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized: User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON format"]);
        exit;
    }

    if (empty($data['message']) || empty($data['suggestion'])) {
        http_response_code(400);
        echo json_encode(["error" => "Both 'message' and 'suggestion' fields are required"]);
        exit;
    }

    $message = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');
    $suggestion = htmlspecialchars($data['suggestion'], ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, Message, Suggestions) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $message, $suggestion);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["success" => "Thank you for your feedback!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $feedbacks = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($feedbacks);

    $stmt->close();
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);
exit;
?>