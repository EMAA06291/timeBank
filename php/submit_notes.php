<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$content = $_POST['content'] ?? '';
$receiver_id = $_POST['receiver_id'] ?? ''; 

if (empty($content) || empty($receiver_id)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

$sender_id = $_SESSION['user_id'];
$user_id = $sender_id;

$stmt = $conn->prepare("INSERT INTO notes (user_id, content, sender_id, receiver_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $content, $sender_id, $receiver_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Note added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add note"]);
}

$stmt->close();
$conn->close();
?>