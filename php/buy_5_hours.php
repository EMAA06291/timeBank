<?php
session_start();
header("Content-Type: application/json");
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized. User not logged in."]);
    exit;
}

$user_ID = $_SESSION['user_id'];
$hours = 5;
$price = 10;

try {
    $stmt = $conn->prepare("INSERT INTO hours_purchased (user_id, purchased_hours, purchase_date) VALUES (?, ?, NOW())");
    $stmt->execute([$user_ID, $hours]);

    $updateStmt = $conn->prepare("UPDATE users SET Time_credits = Time_credits + ? WHERE user_ID = ?");
    $updateStmt->execute([$hours, $user_ID]);

    echo json_encode([
        "message" => "$hours hours purchased and time credit updated successfully",
        "data" => [
            "user_id" => $user_ID,
            "hours" => $hours,
            "price" => $price
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>