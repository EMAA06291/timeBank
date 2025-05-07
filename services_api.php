<?php
require 'config.php';
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_SESSION['services_data'])) {
        $stmt = $conn->prepare("SELECT * FROM services");
        $stmt->execute();
        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);
        
        $formattedServices = array_map(function($service) {
            return [
                'service_id' => $service['service_id'],
                'name' => $service['name'],
                'description' => $service['description'],
                'price' => $service['price'],
                'category' => $service['category']
            ];
        }, $services);
        
        $_SESSION['services_data'] = $formattedServices;
        $_SESSION['services_last_updated'] = time();
    }
    
    echo json_encode($_SESSION['services_data']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['name']) || empty($data['description']) || empty($data['price'])) {
        echo json_encode(["error" => "Service name, description and price are required"]);
        http_response_code(400);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO services (name, description, price, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $data['name'], $data['description'], $data['price'], $data['category'] ?? '');
    
    if ($stmt->execute()) {
        unset($_SESSION['services_data']);
        echo json_encode(["success" => "Service added successfully"]);
        http_response_code(201);
    } else {
        echo json_encode(["error" => "Failed to add service"]);
        http_response_code(500);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $service_id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$service_id) {
        echo json_encode(["error" => "Service ID is required"]);
        http_response_code(400);
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE services SET name=?, description=?, price=?, category=? WHERE service_id=?");
    $stmt->bind_param("ssdsi", $data['name'], $data['description'], $data['price'], $data['category'], $service_id);
    
    if ($stmt->execute()) {
        unset($_SESSION['services_data']);
        echo json_encode(["success" => "Service updated successfully"]);
    } else {
        echo json_encode(["error" => "Failed to update service"]);
        http_response_code(500);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $service_id = $_GET['id'] ?? null;
    
    if (!$service_id) {
        echo json_encode(["error" => "Service ID is required"]);
        http_response_code(400);
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM services WHERE service_id=?");
    $stmt->bind_param("i", $service_id);
    
    if ($stmt->execute()) {
        unset($_SESSION['services_data']);
        echo json_encode(["success" => "Service deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete service"]);
        http_response_code(500);
    }
    exit;
}

echo json_encode(["error" => "Invalid request"]);
http_response_code(404);
?>