<?php

require_once 'db.php';
header('Content-Type: application/json');

$notification_id = $_GET['notification_id'] ?? null;

if (!$notification_id) {
    echo json_encode([
        "success" => false,
        "message" => "Notification ID is required"
    ]);
    exit;
}


$sql = "UPDATE notifications SET Is_read = 1 WHERE Notification_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $notification_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Notification marked as read"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No notification updated (maybe ID is wrong)"
    ]);
}
?>
