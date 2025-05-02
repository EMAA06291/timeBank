<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Only POST requests are allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['Request_ID'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Request_ID is required"]);
    exit;
}

$requestId = $data['Request_ID'];

$sql = "UPDATE requests SET Status = 'completed' WHERE Request_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Request marked as completed"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$conn->close();
?>
