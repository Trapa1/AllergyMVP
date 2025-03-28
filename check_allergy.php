<?php
session_start();
header('Content-Type: application/json');
$db = new PDO('sqlite:database.sqlite');

if (!isset($_SESSION['user_id']) || !isset($_GET['barcode'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$barcode = $_GET['barcode'];

// Get user's allergies
$stmt = $db->prepare("SELECT allergies FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_allergies = explode(", ", strtolower($user['allergies']));

// Find the ingredient from barcode
$stmt = $db->prepare("SELECT name FROM ingredients WHERE barcode = ?");
$stmt->execute([$barcode]);
$ingredient = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ingredient) {
    $ingredient_name = strtolower($ingredient['name']);

    if (in_array($ingredient_name, $user_allergies)) {
        echo json_encode(["allergic" => true, "ingredient" => $ingredient_name]);
    } else {
        echo json_encode(["allergic" => false]);
    }
} else {
    echo json_encode(["error" => "Barcode not found in database"]);
}
?>
