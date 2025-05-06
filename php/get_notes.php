<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to see your notes.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT note_id, content, sender_id FROM notes WHERE receiver_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Notes sent to you:</h3>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li><strong>From User {$row['sender_id']}:</strong> {$row['content']}</li>";
}
echo "</ul>";

$stmt->close();
$conn->close();
?>