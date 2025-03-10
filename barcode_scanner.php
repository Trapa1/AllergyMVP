<!-- <?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Barcode</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
</head>
<body>
    <h1>Scan Medicine Barcode</h1>
    <div id="scanner-container"></div>
    <p id="output"></p>

    <script>
        Quagga.init({
            inputStream: { name: "Live", type: "LiveStream", target: "#scanner-container" },
            decoder: { readers: ["ean_reader"] }
        }, function(err) {
            if (err) { console.error(err); return; }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            let barcode = result.codeResult.code;
            fetch("check_allergy.php?barcode=" + barcode)
            .then(res => res.json())
            .then(data => {
                document.getElementById("output").innerHTML = data.allergic ? "⚠ You are allergic!" : "✅ Safe to use";
            });
        });
    </script>
</body>
</html> -->



<?php
session_start();
require 'vendor/autoload.php';

// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user allergies
$stmt = $db->prepare("SELECT allergies FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_allergies = explode(',', strtolower(trim($user['allergies'] ?? ''))); // ✅ Ensure lowercase and no spaces
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Medicine Barcode</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
        video { width: 80%; border: 2px solid black; }
        #result { font-size: 20px; margin-top: 20px; }
        .warning { color: red; font-weight: bold; }
        .safe { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Scan Medicine Barcode</h1>

    <video id="scanner"></video>
    <p id="result">Waiting for scan...</p>

    <script>
    let allergies = <?= json_encode($user_allergies) ?>; // User's allergy list

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#scanner")
        },
        decoder: {
            readers: ["ean_reader"]
        }
    }, function(err) {
        if (err) {
            console.error(err);
            document.getElementById("result").innerText = "Error initializing scanner!";
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function(result) {
        let barcode = result.codeResult.code;
        document.getElementById("result").innerHTML = `Scanned: <strong>${barcode}</strong>`;

        fetch(`check_barcode.php?barcode=${barcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.found) {
                let medicineName = data.medicine.trim().toLowerCase();  // ✅ Convert medicine name to lowercase
                let medicineIngredients = data.ingredients.split(',').map(i => i.trim().toLowerCase()); // ✅ Normalize case
                let userAllergies = allergies.map(a => a.trim().toLowerCase()); // ✅ Normalize allergies

                // ✅ Fix: Check if the user is allergic to the medicine name
                let allergicToMedicine = userAllergies.includes(medicineName);
                let allergicIngredients = userAllergies.filter(a => medicineIngredients.includes(a)); // ✅ Match ingredients

                console.log("🔹 Medicine Name:", medicineName);
                console.log("User Allergies:", userAllergies);
                console.log("Medicine Ingredients:", medicineIngredients);
                console.log("Matching Allergies:", allergicIngredients);

                if (allergicToMedicine || allergicIngredients.length > 0) {
                    let alertMessage = allergicToMedicine 
                        ? `⚠ WARNING: You are allergic to ${medicineName}!`
                        : `⚠ WARNING: You are allergic to ${allergicIngredients.join(', ')}!`;
                    document.getElementById("result").innerHTML += `<p class='warning'>${alertMessage}</p>`;
                } else {
                    document.getElementById("result").innerHTML += `<p class='safe'>✅ Safe to use.</p>`;
                }
            } else {
                document.getElementById("result").innerHTML += "<p class='warning'>⚠ Medicine not found!</p>";
            }
        })
        .catch(error => console.error('Error:', error));
    });
    </script>

    <p><a href="user_profile.php">Go Back</a></p>
</body>
</html>


