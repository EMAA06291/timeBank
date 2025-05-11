<?php
session_start();
header("Content-Type: application/json");
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized. User not logged in."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

$user_ID = $_SESSION['user_id'];
$hours = 10;
$price = 20;

try {
    $stmt = $conn->prepare("INSERT INTO hours_purchased (user_id, purchased_hours, purchase_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_ID, $hours);
    $stmt->execute();

    $updateStmt = $conn->prepare("UPDATE users SET Time_credits = Time_credits + ? WHERE user_ID = ?");
    $updateStmt->bind_param("ii", $hours, $user_ID);
    $updateStmt->execute();

    $getCredits = $conn->prepare("SELECT Time_credits FROM users WHERE user_ID = ?");
    $getCredits->bind_param("i", $user_ID);
    $getCredits->execute();
    $result = $getCredits->get_result();
    $row = $result->fetch_assoc();
    $total_credits = $row['Time_credits'];

    echo json_encode([
        "message" => "$hours hours purchased and time credit updated successfully",
        "data" => [
            "user_id" => $user_ID,
            "hours" => $hours,
            "price" => $price,
            "total_credits" => $total_credits
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
