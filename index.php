<!-- <?php
// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Fetch medicines
$query = $db->query("SELECT * FROM medicines");
$medicines = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <nav>
    <a href="index.php">Home</a> | 
    <a href="user_profile.php">User Profile</a> | 
    <a href="search.php">Allergy Search</a>
</nav>

    <title>Allergy MVP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Allergy MVP</h1>
    <h2>List of Medicines and Associated Allergies</h2>
    <table>
        <tr>
            <th>Medicine</th>
            <th>Affiliated Allergies</th>
            <th>QR Code</th>
        </tr>
        <?php foreach ($medicines as $medicine): ?>
        <tr>
            <td><?= htmlspecialchars($medicine['name']) ?></td>
            <td><?= htmlspecialchars($medicine['affiliated_allergies']) ?></td>
            <td>
            <img src="generate_qr.php?medicine=<?= urlencode($medicine['name']) ?>" width="100">
        </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
</body>
</html>
 -->

 <?php
// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Fetch medicines
$query = $db->query("SELECT * FROM medicines");
$medicines = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allergy MVP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        img { width: 100px; height: 100px; } /* Ensure QR codes are visible */
        nav { margin-bottom: 20px; }
        nav a { margin-right: 10px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Home</a> | 
        <a href="user_profile.php">User Profile</a> | 
        <a href="barcode_scanner.php">Scan here</a>
    </nav>

    <h1>Allergy MVP</h1>
    <h2>List of Medicines and Associated Allergies</h2>
    
    <table>
        <tr>
            <th>Medicine</th>
            <th>Affiliated Allergies</th>
            <th>QR Code</th>
        </tr>
        <?php foreach ($medicines as $medicine): ?>
        <tr>
            <td><?= htmlspecialchars($medicine['name']) ?></td>
            <td><?= htmlspecialchars($medicine['affiliated_allergies']) ?></td>
            <td>
                <img src="generate_qr.php?medicine=<?= urlencode($medicine['name']) ?>" alt="QR Code">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
