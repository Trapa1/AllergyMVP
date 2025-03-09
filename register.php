<?php
// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $allergies = $_POST['allergies']; // User enters comma-separated allergies

    // Insert user data into database
    $stmt = $db->prepare("INSERT INTO users (name, age, gender, allergies) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $age, $gender, $allergies]);

    echo "<p>User registered successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>User Registration</h2>
    <form action="register.php" method="post">
        <label>Name:</label> <input type="text" name="name" required><br>
        <label>Age:</label> <input type="number" name="age" required><br>
        <label>Gender:</label> 
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>
        <label>Allergies (comma-separated):</label> 
        <input type="text" name="allergies" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
