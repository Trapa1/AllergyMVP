<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

$db = new PDO('sqlite:database.sqlite');

$error = '';

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    // Look for user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['language'] = $user['language'] ?? 'en';
        header("Location: index.php");
        exit;
    } else {
        $error = $lang['login_invalid'];
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $lang['login_title'] ?> | Allergy Alert</title>
  <link rel="stylesheet" href="css/design.css">
</head>
<body>

<nav>
  <a href="index.php" class="logo"><?= $lang['app_name'] ?></a>
  <div class="nav-links">
    <a href="index.php"><?= $lang['home'] ?></a>
    <a href="register.php" class="btn-login"><?= $lang['register'] ?></a>
  </div>
  <div class="menu-icon" onclick="toggleMenu()">☰</div>
  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php"><?= $lang['home'] ?></a>
    <a href="register.php"><?= $lang['register'] ?></a>
  </div>
</nav>

<section class="register-container">
  <div class="register-box">
    <h1><?= $lang['login_title'] ?></h1>
    <p><?= $lang['login_intro'] ?></p>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="email"><?= $lang['email'] ?>:</label>
      <input type="email" name="email" id="email" required>

      <label for="password"><?= $lang['password'] ?>:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" class="btn-register"><?= $lang['login'] ?></button>
    </form>

    <p><?= $lang['no_account'] ?> <a href="register.php"><?= $lang['register_here'] ?></a></p>
  </div>
</section>

<script>
function toggleMenu() {
  const menu = document.getElementById("mobileMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>
