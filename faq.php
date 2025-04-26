<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8" />
  <title><?= $lang['faq_title'] ?> | <?= $lang['app_name'] ?? 'Allergy Alert' ?></title>
  <link rel="stylesheet" href="css/design.css">
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">


<nav class="minimal-nav">
  <div class="minimal-container">
    <a href="index.php">← <?= $lang['home'] ?? 'Home' ?></a>
  </div>
</nav>

<section class="faq-section" style="max-width: 800px; margin: 100px auto 50px; padding: 0 20px;">
  <h1 style="color: #800020; font-size: 32px;"><?= $lang['faq_title'] ?></h1>

  <!-- App Usage -->
  <div class="faq-item">
    <h3><?= $lang['faq_q1'] ?></h3>
    <p><?= $lang['faq_a1'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q2'] ?></h3>
    <p><?= $lang['faq_a2'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q3'] ?></h3>
    <p><?= $lang['faq_a3'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q4'] ?></h3>
    <p><?= $lang['faq_a4'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q5'] ?></h3>
    <p><?= $lang['faq_a5'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q6'] ?></h3>
    <p><?= $lang['faq_a6'] ?></p>
  </div>

  <!-- Allergy Knowledge -->
  <div class="faq-item">
    <h3><?= $lang['faq_q7'] ?></h3>
    <p><?= $lang['faq_a7'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q8'] ?></h3>
    <p><?= $lang['faq_a8'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q9'] ?></h3>
    <p><?= $lang['faq_a9'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q10'] ?></h3>
    <p><?= $lang['faq_a10'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q11'] ?></h3>
    <p><?= $lang['faq_a11'] ?></p>
  </div>

  <div class="faq-item">
    <h3><?= $lang['faq_q12'] ?></h3>
    <p><?= $lang['faq_a12'] ?></p>
  </div>

</section>
</body>
</html>



