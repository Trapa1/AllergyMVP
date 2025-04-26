<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;

session_destroy(); // End session
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['logged_out_title'] ?? 'Logged Out' ?></title>
    <link rel="stylesheet" href="css/design.css">
</head>
<body class="<?= $darkMode ? 'dark-mode home' : 'home' ?>">
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage !== 'index.php'): ?>
  <nav class="minimal-nav">
    <div class="minimal-container">
      <a href="index.php">← Home</a>
    </div>
  </nav>
<?php endif; ?>
    <div class="logout-container">
        <h1><?= $lang['logged_out_message'] ?? "You've been logged out!" ?></h1>
        <p><?= $lang['thank_you'] ?? 'Thank you for using' ?> <strong>AllergyAlert</strong>. 😊</p>
        <div class="logout-buttons">
            <a href="index.php" class="btn">🏠 <?= $lang['home'] ?? 'Home' ?></a>
            <a href="register.php" class="btn">📝 <?= $lang['register'] ?? 'Register' ?></a>
            <a href="login.php" class="btn">🔑 <?= $lang['login'] ?? 'Login' ?></a>
        </div>
    </div>
</body>
</html>



