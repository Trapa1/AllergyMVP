<?php
session_start();

// 🌐 Language switching via GET
if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
    header("Location: settings.php");
    exit;
}

$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile)
    ? require $langFile
    : require __DIR__ . "/language/en.php";

require 'vendor/autoload.php';
$db = new PDO('sqlite:database.sqlite');

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;

// 🧹 Clear History
if (isset($_GET['clear_history']) && isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("DELETE FROM scans WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    header("Location: settings.php?cleared=1");
    exit;
}

// ❌ Delete Account
if (isset($_GET['delete_account']) && isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    session_destroy();
    header("Location: goodbye.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $lang['settings'] ?? 'Settings' ?></title>
  <link rel="stylesheet" href="css/design.css">
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">

<!-- 🔙 Navbar (simple back to Home) -->
<nav class="minimal-nav">
  <div class="minimal-container">
    <a href="index.php">← <?= $lang['home'] ?? 'Home' ?></a>
  </div>
</nav>

<!-- 🔧 Settings Box -->
<div class="profile-container">
  <div class="profile-box">
    <h1>⚙️ <?= $lang['settings'] ?? 'Settings' ?></h1>

    <?php if (isset($_GET['cleared'])): ?>
      <p class="success">✅ <?= $lang['history_cleared'] ?? 'History cleared!' ?></p>
    <?php endif; ?>

    <!-- 🌑 Dark Mode Toggle -->
    <label style="display: block; margin-top: 15px;">
      <input type="checkbox" id="dark-mode-toggle" <?= $darkMode ? 'checked' : '' ?>> 🌙 <?= $lang['dark_mode'] ?? 'Dark Mode' ?>
    </label>

    <!-- 🌍 Language Selector -->
    <label for="lang" style="display: block; margin-top: 15px;">
      <?= $lang['language'] ?? 'Language' ?>:
    </label>
    <select id="lang" name="lang">
      <option value="en" <?= $language == 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
      <option value="fr" <?= $language == 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
      <option value="ro" <?= $language == 'ro' ? 'selected' : '' ?>>🇷🇴 Română</option>
      <option value="de" <?= $language == 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
      <option value="es" <?= $language == 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
      <option value="it" <?= $language == 'it' ? 'selected' : '' ?>>🇮🇹 Italiano</option>
      <option value="pt" <?= $language == 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
    </select>

    <!-- 🧹 Clear History -->
    <br><br>
    <a href="settings.php?clear_history=1" onclick="return confirm('Are you sure you want to clear your scan history?')">
      <button>🧹 <?= $lang['clear_history'] ?? 'Clear Scan History' ?></button>
    </a>

    <!-- ❌ Delete Account -->
    <br><br>
    <button onclick="confirmDelete()">❌ <?= $lang['delete_account'] ?? 'Delete Account' ?></button>
  </div>
</div>

<!-- 💡 Script -->
<script>
  // 🌑 Dark Mode toggle
  document.getElementById('dark-mode-toggle').addEventListener('change', function () {
    const darkModeEnabled = this.checked;

    fetch('dark_mode.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'dark_mode=' + darkModeEnabled
    }).then(() => {
      // After changing dark mode, reload page
      location.reload();
    });
  });

  // 🌍 Language instant switch
  document.getElementById('lang').addEventListener('change', function () {
    window.location.href = 'settings.php?lang=' + this.value;
  });

  // ❌ Delete confirmation
  function confirmDelete() {
    if (confirm("Are you sure you want to delete your account?")) {
      alert("Thank you. We hope you’ll come back! 💔");
      window.location.href = "settings.php?delete_account=1";
    }
  }
</script>

</body>
</html>




