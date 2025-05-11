
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); 

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");

require_once 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

$stmt = $conn->prepare("SELECT user_ID, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Incorrect password"]);
    exit;
}

$_SESSION['user_id'] = $user['user_ID'];

echo json_encode(["success" => true, "message" => "Login successful"]);

$stmt->close();
$conn->close();
?>
