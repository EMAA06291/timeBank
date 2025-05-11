<?php
session_start();
include 'db.php';

header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        s.Service_ID,
        s.Title,
        s.Description,
        s.Hours,
        s.Views,
        u.User_ID,
        u.Name AS User_Name
    FROM services s
    JOIN users u ON s.User_ID = u.User_ID
    WHERE s.User_ID = ?
    ORDER BY s.Service_ID DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to prepare SQL"
    ]);
    exit;
}

$stmt->bind_param("i", $user_ID);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

if (count($services) > 0) {
    echo json_encode([
        "status" => "success",
        "data" => $services
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No services found"
    ]);
}

$stmt->close();
$conn->close();
?>