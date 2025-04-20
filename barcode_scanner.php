<?php
session_start();

// Load language file
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

require 'vendor/autoload.php';

// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user allergies
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT allergies FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_allergies = explode(',', strtolower(trim($user['allergies'] ?? '')));
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['scan'] ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
        video { width: 80%; border: 2px solid black; }
        .warning { color: red; font-weight: bold; }
        .safe { color: green; font-weight: bold; }
        #result { margin-top: 20px; font-size: 18px; }
    </style>
</head>
<body>

<h1><?= $lang['scan'] ?></h1>
<video id="scanner"></video>
<p id="result"><?= $lang['waiting'] ?></p>

<script>
const allergies = <?= json_encode($user_allergies) ?>.map(a => a.trim().toLowerCase());
let lastScanned = "";

Quagga.init({
    inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector('#scanner')
    },
    decoder: {
        readers: ["ean_reader"]
    }
}, function (err) {
    if (err) {
        console.error(err);
        return;
    }
    Quagga.start();
});

Quagga.onDetected(function (result) {
    const barcode = result.codeResult.code;
    if (barcode === lastScanned) return;
    lastScanned = barcode;

    const resultDiv = document.getElementById("result");
    resultDiv.innerHTML = `Scanned: <strong>${barcode}</strong><br><?= $lang['checking'] ?>`;

    fetch(`check_barcode.php?barcode=${barcode}`)
        .then(response => response.json())
        .then(data => {
            const productName = data.name || "Unknown";
            const ingredients = data.ingredients || "";

            if (!data.found) {
                resultDiv.innerHTML = `
        Scanned: <strong>${barcode}</strong><br><?= $lang['checking'] ?>
        <p class="warning">❌ <?= $lang['not_found'] ?></p>
    `;
                return;
            }

            const productIngredients = ingredients
                .toLowerCase()
                .split(/[,\.;]/)
                .map(i => i.trim())
                .filter(i => i.length > 0);

            let matches = [];

            if (ingredients.trim() !== "") {
                matches = allergies.filter(allergy =>
                    productIngredients.some(ingredient =>
                        ingredient.includes(allergy)
                    )
                );
            }

            // ✅ Log the scan
            fetch('log_scan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    barcode,
                    product_name: productName,
                    ingredients,
                    matches
                })
            });

            resultDiv.innerHTML += `
                <p><strong><?= $lang['product'] ?>:</strong> ${productName}</p>
                <p><strong><?= $lang['ingredients'] ?>:</strong> ${ingredients || '<em><?= $lang['no_ingredients'] ?></em>'}</p>
            `;

            if (matches.length > 0 && matches.join(', ').trim() !== "") {
                resultDiv.innerHTML += `
                    <p class="warning">⚠ <?= $lang['alert'] ?> ${matches.join(', ')}</p>
                `;
            } else {
                resultDiv.innerHTML += `
                    <p class="safe">✅ <?= $lang['safe'] ?></p>
                `;
            }

            
    // 🧠 Show Alternatives ONLY if allergy matches exist
if (matches.length > 0 && data.alternatives && data.alternatives.length > 0) {
    const altList = data.alternatives
        .map(name => `<li>${name}</li>`)
        .join('');
    resultDiv.innerHTML += `
        <p><strong>💡 Suggested alternatives:</strong></p>
        <ul>${altList}</ul>
    `;
}


        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById("result").innerHTML += `<p class='warning'>❌ <?= $lang['error'] ?></p>`;
        });
});
</script>

<p><a href="user_profile.php">← <?= $lang['back'] ?></a></p>
</body>
</html>


