<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $user_ID = isset($_POST['user_ID']) ? $_POST['user_ID'] : null;

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
}
?>