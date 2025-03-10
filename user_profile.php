<!-- <?php
// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Fetch the latest user profile (if exists)
$stmt = $db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize user variables
$name = $user['name'] ?? '';
$age = $user['age'] ?? '';
$gender = $user['gender'] ?? '';
$allergies = $user['allergies'] ?? '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies'])); // Store in lowercase

    if ($user) {
        // Update existing user profile
        $stmt = $db->prepare("UPDATE users SET name = ?, age = ?, gender = ?, allergies = ? WHERE id = ?");
        $stmt->execute([$name, $age, $gender, $allergies, $user["id"]]);
    } else {
        // Insert new user profile
        $stmt = $db->prepare("INSERT INTO users (name, age, gender, allergies) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $age, $gender, $allergies]);
    }

    echo "<p style='color:green;'>Profile saved successfully!</p>";

    // Refresh the latest user data after saving
    $stmt = $db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        input, select { padding: 8px; margin: 5px; }
    </style>
</head>
<body>
    <h1>User Profile</h1>
    <form method="POST">
        <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required></label><br>
        <label>Age: <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required></label><br>
        <label>Gender: 
            <select name="gender">
                <option value="Male" <?= ($gender == "Male") ? "selected" : "" ?>>Male</option>
                <option value="Female" <?= ($gender == "Female") ? "selected" : "" ?>>Female</option>
                <option value="Other" <?= ($gender == "Other") ? "selected" : "" ?>>Other</option>
            </select>
        </label><br>
        <label>Allergies (comma-separated): <input type="text" name="allergies" value="<?= htmlspecialchars($allergies) ?>" required></label><br>
        <button type="submit">Save Profile</button>
    </form>

    <h2>Latest User</h2>
    <?php if ($user): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($user['age']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']) ?></p>
        <p><strong>Allergies:</strong> <?= htmlspecialchars($user['allergies']) ?></p>
    <?php else: ?>
        <p>No user profile saved yet.</p>
    <?php endif; ?>
</body>
</html> -->


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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/design.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="barcode_scanner.php">Scan Medicine</a>
        <a href="logout.php">Logout</a>
    </nav>

    <section class="container">
        <h1>Update Your Profile</h1>

        <?php if (isset($_GET['success'])): ?>
            <p class="success">Profile updated successfully!</p>
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
            <label>Allergies (comma-separated): <input type="text" name="allergies" value="<?= htmlspecialchars($allergies) ?>" required></label>
            <button type="submit">Save Profile</button>
        </form>
    </section>
</body>
</html>
