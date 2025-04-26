<?php
session_start();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $lang['about_title'] ?? 'About' ?> | <?= $lang['app_name'] ?></title>
  <link rel="stylesheet" href="css/design.css">
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">


<!-- ✅ About Section -->
<section class="info content-wrapper" style="padding-left: 30px;">
  <h1><?= $lang['about_title'] ?></h1>

  <p><?= $lang['about_paragraph1'] ?></p>
  <p><?= $lang['about_paragraph2'] ?></p>
  <p><?= $lang['about_paragraph3'] ?></p>

  <ul>
    <li><?= $lang['about_bullet1'] ?></li>
    <li> <?= $lang['about_bullet2'] ?></li>
    <li> <?= $lang['about_bullet3'] ?></li>
    <li> <?= $lang['about_bullet4'] ?></li>
  </ul>

  <p><?= $lang['about_paragraph4'] ?></p>

  <h3> <?= $lang['about_mission_heading'] ?></h3>
  <p><?= $lang['about_mission_text'] ?></p>

  <h3> <?= $lang['about_contact_heading'] ?></h3>
  <p><?= $lang['about_contact_text'] ?></p>
</section>

<!-- ✅ Footer -->
<footer>
  <p>&copy; 2025 Allergy Alert | <?= $lang['footer'] ?></p>
</footer>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage !== 'index.php'): ?>
  <nav class="minimal-nav">
    <div class="minimal-container">
      <a href="index.php">← <?= $lang['home'] ?></a>
    </div>
  </nav>
<?php endif; ?>
</body>
</html>




