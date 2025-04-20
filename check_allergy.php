<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Step 1: Get the barcode from the URL
$barcode = $_GET['barcode'] ?? '';

if (!$barcode) {
    echo json_encode(["error" => "Barcode is missing"]);
    exit;
}


// Step 4: Check if the product was found
if (!isset($data['product']['product_name'])) {
    echo json_encode(["found" => false, "message" => "Product not found"]);
    exit;
}

// Step 5: Extract info
$medicineName = strtolower(trim($data['product']['product_name']));
$ingredients = isset($data['product']['ingredients_text']) ? strtolower($data['product']['ingredients_text']) : '';

// Step 6: Return info in JSON
echo json_encode([
    "found" => true,
    "medicine" => $medicineName,
    "ingredients" => $ingredients
]);
?>
