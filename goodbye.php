<?php
session_start();
session_destroy();

// Load language
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";
// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['goodbye_title'] ?? 'Goodbye!' ?></title>
  <link rel="stylesheet" href="css/design.css" />
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">

  <div class="profile-container">
    <div class="profile-box">
      <h1>👋 <?= $lang['goodbye_message'] ?? 'Thank you for using Allergy Alert!' ?></h1>
      <p><?= $lang['goodbye_text'] ?? 'We hope to see you again. Stay safe!' ?></p>
      <a href="index.php" class="btn-auth">← <?= $lang['go_home'] ?? 'Go back to Home' ?></a>
    </div>
  </div>
</body>
</html>

