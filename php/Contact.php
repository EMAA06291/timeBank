<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, sent_at, user_id) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->bind_param("sssi", $name, $email, $message, $user_id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Message sent successfully"
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
            "message" => "All fields are required"
        ]);
    }
}
?>