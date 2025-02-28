<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['access_token']) || !isset($data['role'])) {
    echo json_encode(["error" => "Invalid session data"]);
    exit;
}

$_SESSION['access_token'] = $data['access_token'];
$_SESSION['role'] = $data['role'];

echo json_encode(["status" => "success"]);
?>
