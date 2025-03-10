<?php
session_start();
$db = new PDO('sqlite:database.sqlite');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);

    // Check if user exists
    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
        exit;
    } else {
        $error_message = "User not found. Please register.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/design.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </nav>

    <section class="container">
        <h1>Login</h1>
        <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form method="POST">
            <label>Username: <input type="text" name="name" required></label>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </section>
</body>
</html>
