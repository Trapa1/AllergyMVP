<?php
header('Content-Type: application/json');
session_start();

function normalizeGTIN($code) {
    return ltrim($code, '0');
}

$barcode = $_GET['barcode'] ?? '';
$barcode = normalizeGTIN(trim($barcode));
$found = false;
$result = [];

// Load GTIN to AMPPID map
$gtinPath = __DIR__ . '/data/gtin_data.csv';
$amppid = null;

if (($handle = fopen($gtinPath, "r")) !== FALSE) {
    fgetcsv($handle); // skip header
    while (($data = fgetcsv($handle)) !== FALSE) {
        $gtin = normalizeGTIN(trim($data[0]));
        if ($gtin === $barcode) {
            $amppid = trim($data[1]);
            $found = true;
            break;
        }
    }
    fclose($handle);
}

// Initialize values
$medicineName = "Unknown";
$ingredients = "";
$matches = [];

// Load user's allergies
$user_allergies = [];
if (isset($_SESSION['user_id'])) {
    $db = new PDO('sqlite:database.sqlite');
    $stmt = $db->prepare("SELECT allergies FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_allergies = explode(',', strtolower(trim($user['allergies'] ?? '')));
}

// Try NHS AMPP info
if ($found && $amppid) {
    $amppInfoPath = __DIR__ . '/data/ampp_info.csv';
    if (($handle2 = fopen($amppInfoPath, "r")) !== FALSE) {
        fgetcsv($handle2); // skip header
        while (($row = fgetcsv($handle2)) !== FALSE) {
            if (trim($row[0]) === $amppid) {
                $medicineName = $row[1];
                $ingredients = $row[2] ?? "";
                break;
            }
        }
        fclose($handle2);
    }

    // Fallback: Try ingredients_info.csv
    if (empty($ingredients)) {
        $infoFile = __DIR__ . '/data/ingredients_info.csv';
        if (file_exists($infoFile)) {
            if (($handle3 = fopen($infoFile, "r")) !== FALSE) {
                fgetcsv($handle3); // skip header
                $collected = [];
                while (($row = fgetcsv($handle3)) !== FALSE) {
                    if (trim($row[0]) === $amppid && !empty($row[1])) {
                        $collected[] = trim($row[1]);
                    }
                }
                fclose($handle3);
                if (!empty($collected)) {
                    $ingredients = implode(', ', array_unique($collected));
                }
            }
        }
    }

    // Fallback: Try amppid_ingredient_mapping.csv
    if (empty($ingredients)) {
        $mapFile = __DIR__ . '/data/amppid_ingredient_mapping.csv';
        if (file_exists($mapFile)) {
            if (($handle4 = fopen($mapFile, "r")) !== FALSE) {
                fgetcsv($handle4); // skip header
                $collected = [];
                while (($row = fgetcsv($handle4)) !== FALSE) {
                    if (trim($row[0]) === $amppid && !empty($row[1])) {
                        $collected[] = trim($row[1]);
                    }
                }
                fclose($handle4);
                if (!empty($collected)) {
                    $ingredients = implode(', ', array_unique($collected));
                }
            }
        }
    }
}

// Fallback to OpenFoodFacts
if ($ingredients === "") {
    $url = "https://world.openfoodfacts.org/api/v0/product/$barcode.json";
    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['product']['product_name'])) {
            $medicineName = $data['product']['product_name'];
            $ingredients = $data['product']['ingredients_text'] ?? "";
        }
    }
}

// 🔎 Allergy Matching
$ingredientList = array_map('trim', preg_split('/[,\.;]/', strtolower($ingredients)));
foreach ($user_allergies as $allergy) {
    $allergy = trim($allergy);
    if (!empty($allergy)) {
        foreach ($ingredientList as $ingredient) {
            if (strpos($ingredient, $allergy) !== false) {
                $matches[] = $allergy;
                break;
            }
        }
    }
}

// 💡 Suggest Alternatives ONLY if user is allergic
$alternatives = [];
if (!empty($matches)) {
    $allMedicineFile = __DIR__ . '/data/ampp_info.csv';
    if (file_exists($allMedicineFile)) {
        if (($altHandle = fopen($allMedicineFile, "r")) !== FALSE) {
            fgetcsv($altHandle); // skip header
            while (($row = fgetcsv($altHandle)) !== FALSE) {
                $altName = $row[1];
                $altIngredients = strtolower($row[2] ?? '');
                if ($altName !== $medicineName) {
                    $conflict = false;
                    foreach ($matches as $match) {
                        if (strpos($altIngredients, $match) !== false) {
                            $conflict = true;
                            break;
                        }
                    }
                    if (!$conflict) {
                        $alternatives[] = $altName;
                    }
                }
                if (count($alternatives) >= 5) break;
            }
            fclose($altHandle);
        }
    }
}

// ✅ Output
// ✅ Output
if ($found || $ingredients !== "") {
    $response = [
        "found" => true,
        "name" => $medicineName,
        "ingredients" => $ingredients,
        "matches" => $matches
    ];

    // Add alternatives ONLY if there are allergy matches AND at least 1 alternative found
    if (!empty($matches) && !empty($alternatives)) {
        $response["alternatives"] = array_unique($alternatives);
    }

    echo json_encode($response);
} else {
    echo json_encode(["found" => false]);
}
