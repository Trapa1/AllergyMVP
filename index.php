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
    </style>
</head>
<body>
    <h1>Allergy MVP</h1>
    <h2>List of Medicines and Associated Allergies</h2>
    <table>
        <tr>
            <th>Medicine</th>
            <th>Affiliated Allergies</th>
        </tr>
        <?php foreach ($medicines as $medicine): ?>
        <tr>
            <td><?= htmlspecialchars($medicine['name']) ?></td>
            <td><?= htmlspecialchars($medicine['affiliated_allergies']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

