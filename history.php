<?php
session_start();


// Load language file
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;


if (file_exists($langFile)) {
    $lang = require $langFile;
} else {
    $lang = require __DIR__ . "/language/en.php";
}

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
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title>📜 <?= $lang['history'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/design.css"> 
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
            td:nth-of-type(1)::before { content: "<?= $lang['product'] ?>"; }
            td:nth-of-type(2)::before { content: "<?= $lang['barcode'] ?>"; }
            td:nth-of-type(3)::before { content: "<?= $lang['ingredients'] ?>"; }
            td:nth-of-type(4)::before { content: "<?= $lang['allergies'] ?>"; }
            td:nth-of-type(5)::before { content: "<?= $lang['scanned_on'] ?>"; }
        }
    </style>
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage !== 'index.php'): ?>
  <nav class="minimal-nav">
    <div class="minimal-container">
      <a href="index.php">← Home</a>
    </div>
  </nav>
<?php endif; ?>

<h1>📜 <?= $lang['history'] ?></h1>
<p>
  <a href="barcode_scanner.php" class="btn-auth">← <?= $lang['back_to_scanner'] ?></a>
</p>


<?php if (count($scans) === 0): ?>
    <p class="gray"><?= $lang['no_scans'] ?></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>📦 <?= $lang['product'] ?></th>
                <th>🔢 <?= $lang['barcode'] ?></th>
                <th>🧪 <?= $lang['ingredients'] ?></th>
                <th>⚠ <?= $lang['allergies'] ?></th>
                <th>⏰ <?= $lang['scanned_on'] ?></th>
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
                        <span class="safe"><?= $lang['none'] ?></span>
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

