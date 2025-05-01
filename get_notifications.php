<?php

require_once 'db.php';
header('Content-Type: application/json');

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "User ID is required"
    ]);
    exit;
}


$sql = "SELECT Notification_ID, Message, Is_read, Created_at 
        FROM notifications 
        WHERE User_ID = ? 
        ORDER BY Created_at DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}



echo json_encode($notifications);
?>
