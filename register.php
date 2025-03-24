


<?php
// Start session
session_start();
$db = new PDO('sqlite:database.sqlite');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies'])); // Store in lowercase

    // Check if user already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$name]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $error_message = "❌ Username already taken. Please choose another.";
    } else {
        // Insert new user
        $stmt = $db->prepare("INSERT INTO users (name, age, gender, allergies) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $age, $gender, $allergies]);

        // Redirect to login page
        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Allergy Alert</title>
    <link rel="stylesheet" href="css/design.css"> <!-- Linking CSS -->
</head>
<body>

    <!-- Navigation -->
    <nav>
        <a href="index.php" class="logo">Allergy Alert</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php" class="btn-login">Login</a>
        </div>
        <div class="menu-icon" onclick="toggleMenu()">☰</div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <!-- Register Form -->
    <section class="register-container">
        <div class="register-box">
            <h1>Create an Account</h1>
            <p>Join Allergy Alert to track and check your medicine allergies.</p>
            
            <?php if (!empty($error_message)): ?>
                <p class="error"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <form action="register.php" method="post">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="age" required>

                <label for="gender">Gender:</label>
                <select name="gender" id="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <label for="allergies">Allergies (comma-separated):</label>
                <input type="text" name="allergies" id="allergies" required>

                <button type="submit" class="btn-register">Register</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </section>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("mobileMenu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>

</body>
</html>
