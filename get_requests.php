<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db.php';


$conn->set_charset("utf8");


$sql = "
    SELECT 
        r.Request_ID,
        r.Requested_time,
        r.Status,

        sender.User_ID AS Sender_ID,
        sender.Name AS Sender_Name,

        receiver.User_ID AS Receiver_ID,
        receiver.Name AS Receiver_Name,

        s.Service_ID,
        s.Title AS Service_Title

    FROM requests r
    JOIN users sender ON r.Sender_ID = sender.User_ID
    JOIN users receiver ON r.Receiver_ID = receiver.User_ID
    JOIN services s ON r.Service_ID = s.Service_ID
    ORDER BY r.Request_ID DESC
";


$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Query error: " . $conn->error
    ]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}


echo json_encode([
    "success" => true,
    "data" => $data
], JSON_UNESCAPED_UNICODE);


$conn->close();
?>