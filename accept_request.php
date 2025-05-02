<?php
header("Access-Control-Allow-Origin: *");
require 'db.php';

if (isset($_GET['id'])) {
    $requestId = $_GET['id'];

    $sql = "UPDATE requests SET Status = 'accepted' WHERE Request_ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $requestId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Request accepted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to accept request."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Query preparation failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Request ID is missing."]);
}

$conn->close();
?>
