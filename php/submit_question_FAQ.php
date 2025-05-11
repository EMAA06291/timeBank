<?php
session_start();
include 'db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];

    // استخدم user_id من السيشن مباشرة بدلًا من POST
    if (isset($_SESSION['user_id'])) {
        $user_ID = $_SESSION['user_id'];
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "User not logged in"
        ]);
        exit;
    }

    if (!empty($question)) {
        $stmt = $conn->prepare("INSERT INTO faq (question, user_ID) VALUES (?, ?)");
        $stmt->bind_param("si", $question, $user_ID);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Question submitted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Database error"
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Question is required"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
}
?>