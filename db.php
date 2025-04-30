<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'timebank';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit;
}
?>