<?php
session_start();
session_destroy(); // Destroy all session data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link rel="stylesheet" href="design.css"> <!-- Make sure this file exists -->
</head>
<body>
    <div class="logout-container">
        <h1>You've been logged out!</h1>
        <p>Thank you for using <strong>AllergyAlert</strong>. See you soon! 😊</p>
        <div class="logout-buttons">
            <a href="index.php" class="btn">🏠 Home</a>
            <a href="register.php" class="btn">📝 Register</a>
            <a href="login.php" class="btn">🔑 Login</a>
        </div>
    </div>
</body>
</html>


