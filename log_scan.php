<?php
session_start();
require 'vendor/autoload.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['barcode'])) {
        throw new Exception("Missing or invalid data");
    }

    $stmt = $db->prepare("
        INSERT INTO scans (user_id, barcode, product_name, ingredients, allergy_matches)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'],
        $data['barcode'],
        $data['product_name'] ?? '',
        $data['ingredients'] ?? '',
        implode(', ', $data['matches'] ?? [])
    ]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

