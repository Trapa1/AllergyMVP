<?php
header('Content-Type: application/json'); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'vendor/autoload.php';

try {
    // Connect to SQLite database
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable detailed error messages

    // Check if barcode is provided
    if (!isset($_GET['barcode']) || empty($_GET['barcode'])) {
        echo json_encode(["error" => "No barcode provided"]);
        exit;
    }

    $barcode = trim($_GET['barcode']);

    // DEBUG: Check if the 'barcode' column exists
    $stmt = $db->query("PRAGMA table_info(medicines);");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');

    if (!in_array('barcode', $columnNames)) {
        echo json_encode(["error" => "The 'barcode' column is missing in the medicines table!"]);
        exit;
    }

    // Query database for the barcode
    $stmt = $db->prepare("SELECT name, affiliated_allergies FROM medicines WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($medicine) {
        echo json_encode([
            "found" => true,
            "medicine" => strtolower(trim($medicine["name"])), // ✅ Convert to lowercase
            "ingredients" => strtolower(trim($medicine["affiliated_allergies"])) // ✅ Convert to lowercase
        ]);
    } else {
        echo json_encode(["found" => false, "message" => "Medicine not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>




