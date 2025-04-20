<?php
session_start();
require 'vendor/autoload.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new PDO('sqlite:database.sqlite');

// Get user's scan history
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM scans WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->execute([$user_id]);
$scans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📜 Scan History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        th {
            background-color: #f5f5f5;
        }
        .warning {
            color: red;
            font-weight: bold;
        }
        .safe {
            color: green;
            font-weight: bold;
        }
        .gray {
            color: #777;
            text-align: center;
        }
        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th {
                display: none;
            }
            td {
                border: none;
                border-bottom: 1px solid #ccc;
                position: relative;
                padding-left: 50%;
                margin-bottom: 10px;
            }
            td::before {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 45%;
                font-weight: bold;
                white-space: nowrap;
            }
            td:nth-of-type(1)::before { content: "Product"; }
            td:nth-of-type(2)::before { content: "Barcode"; }
            td:nth-of-type(3)::before { content: "Ingredients"; }
            td:nth-of-type(4)::before { content: "Allergy Match"; }
            td:nth-of-type(5)::before { content: "Scanned On"; }
        }
    </style>
</head>
<body>

<h1>📜 Scan History</h1>
<p><a href="barcode_scanner.php">← Back to Scanner</a></p>

<?php if (count($scans) === 0): ?>
    <p class="gray">You haven't scanned any products yet.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>📦 Product</th>
                <th>🔢 Barcode</th>
                <th>🧪 Ingredients</th>
                <th>⚠ Allergies</th>
                <th>⏰ Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($scans as $scan): ?>
            <tr>
                <td><?= htmlspecialchars($scan['product_name']) ?></td>
                <td><?= htmlspecialchars($scan['barcode']) ?></td>
                <td title="<?= htmlspecialchars($scan['ingredients']) ?>">
                    <?= strlen($scan['ingredients']) > 40
                        ? htmlspecialchars(substr($scan['ingredients'], 0, 40)) . '...'
                        : htmlspecialchars($scan['ingredients']) ?>
                </td>
                <td>
                    <?php if (trim($scan['allergy_matches'])): ?>
                        <span class="warning"><?= htmlspecialchars($scan['allergy_matches']) ?></span>
                    <?php else: ?>
                        <span class="safe">None</span>
                    <?php endif; ?>
                </td>
                <td><?= date("Y-m-d H:i", strtotime($scan['timestamp'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
