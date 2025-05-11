 <?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
require_once 'db.php';

$user_ID = $_GET['user_id'] ?? null;

if (!$user_ID) {
    echo json_encode(["success" => false, "message" => "User ID is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT user_ID, full_name, email FROM users WHERE user_ID = ?");
$stmt->bind_param("i", $user_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();

echo json_encode([
    "success" => true,
    "user" => $user
]);

$stmt->close();
$conn->close();