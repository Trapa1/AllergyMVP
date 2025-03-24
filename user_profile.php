<?php
session_start(); // Start the session
require 'vendor/autoload.php';

// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user profile
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables
$name = $user['name'] ?? '';
$age = $user['age'] ?? '';
$gender = $user['gender'] ?? '';
$allergies = $user['allergies'] ?? '';

// Handle form submission (Update Profile)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies'])); // Store in lowercase

    // Update user profile
    $stmt = $db->prepare("UPDATE users SET name = ?, age = ?, gender = ?, allergies = ? WHERE id = ?");
    $stmt->execute([$name, $age, $gender, $allergies, $user_id]);

    // Redirect to avoid form resubmission issues
    header("Location: user_profile.php?success=1");
    exit;
}

// List of common allergies for auto-suggestions
$allergyList = [
    "Penicillin", "Ibuprofen", "Aspirin", "Codeine", "Morphine", "Cephalosporins",
    "Sulfonamides", "Lidocaine", "Local Anesthetics", "Amoxicillin", "Tetracycline",
    "Erythromycin", "NSAIDs", "Beta-Lactams", "Paracetamol", "Corticosteroids"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/design.css">

    <!-- Include jQuery for autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function () {
            var availableAllergies = <?= json_encode($allergyList) ?>;
            $("#allergy-input").autocomplete({
                source: availableAllergies,
                multiple: true
            });
        });
    </script>

</head>
<body>
    <nav>
        <a href="index.php">🏠 Home</a>
        <a href="barcode_scanner.php">📷 Scan Medicine</a>
        <a href="logout.php">🚪 Logout</a>
    </nav>

    <section class="container">
        <h1>👤 Update Your Profile</h1>

        <?php if (isset($_GET['success'])): ?>
            <p class="success">✅ Profile updated successfully!</p>
        <?php endif; ?>

        <form method="POST">
            <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required></label>
            <label>Age: <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required></label>
            <label>Gender:
                <select name="gender">
                    <option value="Male" <?= ($gender == "Male") ? "selected" : "" ?>>Male</option>
                    <option value="Female" <?= ($gender == "Female") ? "selected" : "" ?>>Female</option>
                    <option value="Other" <?= ($gender == "Other") ? "selected" : "" ?>>Other</option>
                </select>
            </label>
            <label>Allergies (comma-separated): 
                <input type="text" id="allergy-input" name="allergies" value="<?= htmlspecialchars($allergies) ?>" required>
            </label>
            <button type="submit">💾 Save Profile</button>
        </form>
    </section>
</body>
</html>
