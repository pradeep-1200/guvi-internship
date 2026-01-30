<?php
header("Content-Type: application/json");

require_once "config/redis.php";
require_once "config/mongo.php";
require_once "config/mysql.php";

$token = $_POST['token'];
$userId = $redis->get($token);

if (!$userId) {
    echo json_encode(["status" => "error", "message" => "Invalid session"]);
    exit;
}

// Fetch Name from MySQL
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$name = $user ? $user['name'] : "";
$stmt->close();

$profile = $profiles->findOne(["user_id" => (int)$userId]);

if ($profile) {
    echo json_encode([
        "status" => "success",
        "data" => [
            "name" => $name,
            "age" => $profile["age"] ?? "",
            "dob" => $profile["dob"] ?? "",
            "contact" => $profile["contact"] ?? ""
        ]
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "data" => [
            "name" => $name,
            "age" => "",
            "dob" => "",
            "contact" => ""
        ]
    ]);
}
