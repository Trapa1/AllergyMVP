<!-- <?php
// Connect to SQLite database
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
        $error_message = "⚠ Username already taken. Choose another.";
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
    <title>Register</title>
    <link rel="stylesheet" href="css/design.css">
    <script>
        function showSuggestions(str) {
            if (str.length === 0) {
                document.getElementById("allergy-suggestions").innerHTML = "";
                return;
            }

            fetch(`allergy_suggestions.php?query=${str}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = data.map(allergy => `<li onclick="selectAllergy('${allergy}')">${allergy}</li>`).join('');
                    document.getElementById("allergy-suggestions").innerHTML = `<ul>${suggestions}</ul>`;
                });
        }

        function selectAllergy(allergy) {
            let inputField = document.getElementById("allergy-input");
            let currentVal = inputField.value;
            let allergies = currentVal ? currentVal.split(', ') : [];

            if (!allergies.includes(allergy)) {
                allergies.push(allergy);
                inputField.value = allergies.join(', ');
            }
            document.getElementById("allergy-suggestions").innerHTML = "";
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>

    <section class="container">
        <h2>User Registration</h2>
        <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="register.php" method="post">
            <label>Name: <input type="text" name="name" required></label>
            <label>Age: <input type="number" name="age" required></label>
            <label>Gender:
                <select name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </label>
            <label>Allergies (comma-separated): 
                <input type="text" id="allergy-input" name="allergies" onkeyup="showSuggestions(this.value)" autocomplete="off" required>
                <div id="allergy-suggestions"></div>
            </label>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </section>
</body>
</html> -->

<?php
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
        $error_message = "Username already taken. Choose another.";
    } else {
        // Insert new user
        $stmt = $db->prepare("INSERT INTO users (name, age, gender, allergies) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $age, $gender, $allergies]);

        // Redirect to login page with success message
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
    <title>Register</title>
    <link rel="stylesheet" href="css/design.css">
    <script>
        function showSuggestions(str) {
            if (str.length === 0) {
                document.getElementById("allergy-suggestions").innerHTML = "";
                return;
            }

            fetch(`allergy_suggestions.php?query=${str}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = data.map(allergy => 
                        `<li onclick="selectAllergy('${allergy}')">${allergy}</li>`
                    ).join('');

                    document.getElementById("allergy-suggestions").innerHTML = `<ul>${suggestions}</ul>`;
                })
                .catch(error => console.error("Error fetching suggestions:", error));
        }

        function selectAllergy(allergy) {
            let inputField = document.getElementById("allergy-input");
            let currentVal = inputField.value;
            let allergies = currentVal ? currentVal.split(', ') : [];

            if (!allergies.includes(allergy)) {
                allergies.push(allergy);
                inputField.value = allergies.join(', ');
            }

            document.getElementById("allergy-suggestions").innerHTML = "";
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>

    <section class="container">
        <h2>User Registration</h2>
        <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="register.php" method="post">
    <label>Name: 
        <input type="text" name="name" id="name" autocomplete="name" required>
    </label>

    <label>Age: 
        <input type="number" name="age" id="age" autocomplete="bday-year" required>
    </label>

    <label>Gender:
        <select name="gender" id="gender" autocomplete="sex">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </label>

    <label>Allergies (comma-separated): 
        <input type="text" id="allergy-input" name="allergies" 
               onkeyup="showSuggestions(this.value)" autocomplete="off" required>
        <div id="allergy-suggestions" class="suggestion-box"></div>
    </label>

    <button type="submit">Register</button>
</form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </section>
</body>
</html>
