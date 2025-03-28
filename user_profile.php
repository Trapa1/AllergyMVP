<?php
// Start the session to track user login status
session_start();

// Include Composer's autoloader for external libraries
require 'vendor/autoload.php';

// Connect to the SQLite database
try {
    $db = new PDO('sqlite:database.sqlite');
    // Set error mode to exceptions to handle errors gracefully
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message and terminate script if connection fails
    die("Database connection failed: " . $e->getMessage());
}

// Check if the user is logged in by verifying the presence of 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Retrieve the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the user's profile information from the database
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables with user data or set to empty strings if not available
$name = $user['name'] ?? '';
$age = $user['age'] ?? '';
$gender = $user['gender'] ?? '';
$allergies = $user['allergies'] ?? '';

// Handle form submission for updating the user profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign form inputs to variables
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies'])); // Convert to lowercase for consistency

    // Update the user's profile information in the database
    $stmt = $db->prepare("UPDATE users SET name = ?, age = ?, gender = ?, allergies = ? WHERE id = ?");
    $stmt->execute([$name, $age, $gender, $allergies, $user_id]);

    // Redirect to the same page with a success flag to prevent form resubmission
    header("Location: user_profile.php?success=1");
    exit;
}

// List of common allergies for autocomplete suggestions
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
    <!-- Link to external CSS file for styling -->
    <link rel="stylesheet" href="css/design.css">

    <!-- Include jQuery and jQuery UI libraries for autocomplete functionality -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- JavaScript for handling autocomplete in the allergies input field -->
    <script>
        $(document).ready(function () {
            // Convert PHP allergy list to JavaScript array
            var availableAllergies = <?= json_encode($allergyList) ?>;

            // Function to split input values by comma and optional space
            function split(val) {
                return val.split(/,\s*/);
            }

            // Function to extract the last term after the last comma
            function extractLast(term) {
                return split(term).pop();
            }

            // Initialize autocomplete on the allergies input field
            $("#allergy-input")
                // Prevent default behavior when pressing TAB if an item is focused
                .on("keydown", function (event) {
                    if (event.keyCode === $.ui.keyCode.TAB &&
                        $(this).autocomplete("instance").menu.active) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    minLength: 1, // Minimum characters before autocomplete activates
                    source: function (request, response) {
                        // Filter the available allergies based on the extracted last term
                        response($.ui.autocomplete.filter(
                            availableAllergies, extractLast(request.term)
                        ));
                    },
                    focus: function () {
                        // Prevent value insertion on focus
                        return false;
                    },
                    select: function (event, ui) {
                        var terms = split(this.value);
                        // Remove the current input
                        terms.pop();
                        // Add the selected item
                        terms.push(ui.item.value);
                        // Add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join(", ");
                        return false;
                    }
                });
        });
    </script>
</head>
<body>
    <!-- Navigation menu -->
    <nav>
        <a href="index.php">🏠 Home</a>
        <a href="barcode_scanner.php">📷 Scan Medicine</a>
        <a href="logout.php">🚪 Logout</a>
    </nav>

    <!-- Main content section -->
    <section class="container">
        <h1>👤 Update Your Profile</h1>

        <!-- Display success message if profile was updated -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">✅ Profile updated successfully!</p>
        <?php endif; ?>

        <!-- User profile update form -->
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

