<?php
require 'config.php';
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user_id = $_GET['user_id'] ?? null;

    if (!isset($_SESSION['courses_cache'])) {
        $sql = "SELECT * FROM learning";
        if ($user_id) {
            $sql .= " WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
        } else {
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $courses = $result->fetch_all(MYSQLI_ASSOC);

        $response = array_map(function($course) {
            return [
                'course_id' => $course['course_id'],
                'title'     => $course['title'],
                'content'   => $course['content'],
                'instructor' => 'Default Instructor',
                'duration'   => 1 
            ];
        }, $courses);

        $_SESSION['courses_cache'] = $response;
        $_SESSION['last_update'] = time();
    }

    echo json_encode($_SESSION['courses_cache']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['title']) || empty($data['content']) || empty($data['user_id'])) {
        echo json_encode(["error" => "Title, content, and user ID are required"]);
        http_response_code(400);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO learning (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $data['title'], $data['content'], $data['user_id']);

    if ($stmt->execute()) {
        unset($_SESSION['courses_cache']);
        echo json_encode(["success" => "Course added successfully"]);
        http_response_code(201);
    } else {
        echo json_encode(["error" => "Add failed: " . $stmt->error]);
        http_response_code(500);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $course_id = $_GET['course_id'] ?? null;
    $user_id = $_GET['user_id'] ?? null;

    if (!$course_id || !$user_id) {
        echo json_encode(["error" => "Course ID and user ID are required"]);
        http_response_code(400);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM learning WHERE course_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $course_id, $user_id);

    if ($stmt->execute()) {
        unset($_SESSION['courses_cache']);
        echo json_encode(["success" => "Course deleted successfully"]);
    } else {
        echo json_encode(["error" => "Delete failed: " . $stmt->error]);
    }
    exit;
}

echo json_encode(["error" => "Invalid endpoint"]);
http_response_code(404);
?>