<?php
error_reporting(0);
header('Content-Type: application/json');
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT Profile_ID, user_ID, Names, profile_pic, skills, about_me, service_offered, created_at FROM my_profile WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    if ($user_data['Names'] !== null) {
        echo json_encode([
            "success" => true,
            "user" => [
                "name" => $user_data['Names'],
                "profile_pic" => $user_data['profile_pic'],
                "skills" => $user_data['skills'],
                "about_me" => $user_data['about_me'],
                "service_offered" => $user_data['service_offered']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "User data is missing"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$stmt->close();
$conn->close();