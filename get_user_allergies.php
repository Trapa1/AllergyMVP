<?php
session_start();
header('Content-Type: application/json');

// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user allergies
$stmt = $db->prepare("SELECT allergies FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(["allergies" => $user['allergies'] ?? ""]);
?>
