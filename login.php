<?php
session_start();
$db = new PDO('sqlite:database.sqlite');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'];

    // Check if user exists
    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: user_profile.php");
        exit;
    } else {
        echo "<p style='color:red;'>User not found. Please register.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label>Username: <input type="text" name="name" required></label>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>

