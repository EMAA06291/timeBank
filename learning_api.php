<?php
require 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Check DB connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(["error" => "GET method required"]));
}

$cache_key = 'courses_' . md5(json_encode($_GET));

if (empty($_SESSION[$cache_key]) || $_SESSION['cache_expiry'] < time()) {
    try {
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
        $course_id = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);

        if ($course_id) {
            $stmt = $conn->prepare("SELECT * FROM learning_material WHERE course_id = ?");
            if ($stmt === false) {
                die(json_encode(["error" => "MySQL prepare error: " . $conn->error]));
            }
            $stmt->bind_param("i", $course_id);
        } elseif ($user_id) {
            $stmt = $conn->prepare("SELECT * FROM learning_material WHERE user_id = ?");
            if ($stmt === false) {
                die(json_encode(["error" => "MySQL prepare error: " . $conn->error]));
            }
            $stmt->bind_param("i", $user_id);
        } else {
            $stmt = $conn->prepare("SELECT * FROM learning_material");
            if ($stmt === false) {
                die(json_encode(["error" => "MySQL prepare error: " . $conn->error]));
            }
        }

        $stmt->execute();
        $_SESSION[$cache_key] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $_SESSION['cache_expiry'] = time() + 300; // Cache for 5 minutes
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        die(json_encode(["error" => "Database error"]));
    }
}

echo json_encode($_SESSION[$cache_key]);
?>
