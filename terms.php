<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $lang['terms_footer_link'] ?? 'Legal Info' ?> | Allergy Alert</title>
  <link rel="stylesheet" href="css/design.css">
</head>
<body class="legal-page">
<nav class="minimal-nav">
  <div class="minimal-container">
    <a href="index.php">← Back to Homepage</a>
  </div>
</nav>


<section class="info">
  <h2>Terms of Service</h2>
  <p>This site is for educational and demonstration purposes only. By using this website, you agree not to rely on the information for medical diagnosis or treatment.</p>

  <h2>Cookies Policy</h2>
  <p>This website uses cookies for session management only. We do not track, store, or share any personal browsing behavior.</p>

  <h2>Privacy Policy</h2>
  <p>All data entered in forms remains securely stored in our local database. None of your personal information is shared or used outside this app.</p>
</section>

<footer class="main-footer">
  <div class="footer-container">
    <p>© 2025 Allergy Alert – Your Safety, Our Priority</p>
    <div class="footer-links">
      <a href="terms.php">Terms & Cookies</a>
    </div>
  </div>
</footer>

</body>
</html>


