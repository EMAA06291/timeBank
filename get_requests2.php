<?php
session_start();
include 'db.php';

header("Content-Type: application/json");


if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User not logged in"
    ]);
    exit;
}

$user_ID = $_SESSION['user_id'];


$sql = "
    SELECT 
        r.Request_ID,
        r.Requested_time,
        r.Status,

        sender.User_ID AS Sender_ID,
        sender.Name AS Sender_Name,

        receiver.User_ID AS Receiver_ID,
        receiver.Name AS Receiver_Name,

        s.Service_ID,
        s.Title AS Service_Title

    FROM requests r
    JOIN users sender ON r.Sender_ID = sender.User_ID
    JOIN users receiver ON r.Receiver_ID = receiver.User_ID
    JOIN services s ON r.Service_ID = s.Service_ID

    WHERE r.Sender_ID = ? OR r.Receiver_ID = ?

    ORDER BY r.Request_ID DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_ID, $user_ID);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

if (count($requests) > 0) {
    echo json_encode([
        "status" => "success",
        "data" => $requests
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No requests found"
    ]);
}

$stmt->close();
$conn->close();
?>