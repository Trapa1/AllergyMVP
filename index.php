
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
    <link rel="stylesheet" href="css/design.css"> <!-- Linking the new CSS file -->
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="user_profile.php">User Profile</a>
        <a href="barcode_scanner.php">Scan Medicine</a>
        <a href="logout.php">Logout</a>
    </nav>

    <section class="container">
        <h1>Welcome to AllergyAllert</h1>
        <p class="description">
            A tool designed to help users check their allergies against common medicines.
            Use the barcode scanner to ensure your safety before taking medication.
        </p>

        <div class="cta-buttons">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Login</a>
        </div>
    </section>

    <section class="info-section">
        <h2>Important Allergy Information</h2>
        <p>Before taking any medication, always check the ingredients.</p>
        <p>Use our barcode scanner to detect if any medicine contains allergens that may be harmful to you.</p>
    </section>
</body>
</html>
