<?php

session_start();
include 'db.php';

header("Content-Type: application/json");

if (isset($_SESSION['user_id'])) {
    $user_ID = $_SESSION['user_id'];
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User not logged in"
    ]);
    exit;
}

$sql = "SELECT Notification_ID, Message, Is_read, Created_at 
        FROM notifications 
        WHERE User_ID = ? 
        ORDER BY Created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_ID);  
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

if (count($notifications) > 0) {
    echo json_encode($notifications);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No notifications found"
    ]);
}

$stmt->close();
$conn->close();
?>