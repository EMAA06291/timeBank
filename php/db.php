<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'timebank-db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}
?>