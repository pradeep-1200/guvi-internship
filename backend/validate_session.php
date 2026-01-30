<?php
header("Content-Type: application/json");

require_once "config/redis.php";

$token = $_POST['token'];

$userId = $redis->get($token);

if ($userId) {
    echo json_encode([
        "status" => "success",
        "user_id" => $userId
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid session"
    ]);
}
