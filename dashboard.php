<?php
require_once 'db.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "User ID is required"
    ]);
    exit;
}

// 1. Active Services
$sql_services = "SELECT COUNT(*) AS active_services FROM services WHERE User_ID = ?";
$stmt1 = $conn->prepare($sql_services);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$result1 = $stmt1->get_result()->fetch_assoc();

// 2. Time Balance
$sql_balance = "SELECT Time_credits FROM users WHERE User_ID = ?";
$stmt2 = $conn->prepare($sql_balance);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result()->fetch_assoc();

// 3. Ongoing Requests
$sql_ongoing = "SELECT COUNT(*) AS ongoing_requests FROM requests 
                WHERE (Sender_id = ? OR Receiver_id = ?) 
                AND Status IN ('pending', 'accepted')";
$stmt3 = $conn->prepare($sql_ongoing);
$stmt3->bind_param("ii", $user_id, $user_id);
$stmt3->execute();
$result3 = $stmt3->get_result()->fetch_assoc();

// 4. Completed Requests
$sql_completed = "SELECT COUNT(*) AS completed_requests FROM requests 
                  WHERE (Sender_id = ? OR Receiver_id = ?) 
                  AND Status = 'completed'";
$stmt4 = $conn->prepare($sql_completed);
$stmt4->bind_param("ii", $user_id, $user_id);
$stmt4->execute();
$result4 = $stmt4->get_result()->fetch_assoc();

// Final Response
echo json_encode([
    "active_services" => $result1['active_services'],
    "time_balance" => $result2['Time_credits'],
    "ongoing_requests" => $result3['ongoing_requests'],
    "completed_requests" => $result4['completed_requests']
]);
?>
