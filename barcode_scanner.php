<?php
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
</html>
