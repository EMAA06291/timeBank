<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');

    if (!empty($question)) {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $dbname = 'faq_system';

        $conn = new mysqli($host, $user, $password, $dbname);
        if ($conn->connect_error) {
            http_response_code(500);
            die("Connection failed: " . $conn->connect_error);
        }

        $question = $conn->real_escape_string($question);
        $sql = "INSERT INTO faq (question) VALUES ('$question')";

        if ($conn->query($sql)) {
            http_response_code(200);
            echo "Question saved!";
        } else {
            http_response_code(500);
            echo "Database error.";
        }

        $conn->close();
    } else {
        http_response_code(400);
        echo "Empty question.";
    }
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>