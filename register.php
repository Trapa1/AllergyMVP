<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

$db = new PDO('sqlite:database.sqlite');
$error_message = '';

if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
    header("Location: register.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies']));
    $language = $_POST['language'] ?? 'en';

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $error_message = $lang['email_exists'];
    } else {
        $stmt = $db->prepare("INSERT INTO users (name, email, password, age, gender, allergies, language) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $age, $gender, $allergies, $language]);
        $_SESSION['language'] = $language;
        header("Location: login.php?registered=1");
        exit;
    }
}

// Autocomplete options
$allergyList = [
    "Penicillin", "Ibuprofen", "Aspirin", "Codeine", "Morphine", "Cephalosporins",
    "Sulfonamides", "Lidocaine", "Local Anesthetics", "Amoxicillin", "Tetracycline",
    "Erythromycin", "NSAIDs", "Beta-Lactams", "Paracetamol", "Corticosteroids"
];
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $lang['register_title'] ?> | Allergy Alert</title>
  <link rel="stylesheet" href="css/design.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script>
    $(document).ready(function () {
        const availableAllergies = <?= json_encode($allergyList) ?>;
        $("#allergies").on("keydown", function () {
            $(this).autocomplete({
                source: function (request, response) {
                    const term = request.term.split(/,\s*/).pop();
                    response($.ui.autocomplete.filter(availableAllergies, term));
                },
                focus: () => false,
                select: function (event, ui) {
                    const terms = this.value.split(/,\s*/);
                    terms.pop();
                    terms.push(ui.item.value);
                    terms.push("");
                    this.value = terms.join(", ");
                    return false;
                }
            });
        });
    });
  </script>
</head>
<body>

<nav>
  <a href="index.php" class="logo"><?= $lang['app_name'] ?></a>
  <div class="nav-links">
    <a href="index.php"><?= $lang['home'] ?></a>
    <a href="login.php" class="btn-login"><?= $lang['login'] ?></a>
  </div>
  <div class="menu-icon" onclick="toggleMenu()">☰</div>
  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php"><?= $lang['home'] ?></a>
    <a href="login.php"><?= $lang['login'] ?></a>
  </div>
</nav>

<section class="register-container">
  <div class="register-box">
    <h1><?= $lang['create_account'] ?></h1>
    <p><?= $lang['join_allergy_alert'] ?></p>

    <!-- Language switcher -->
    <form method="GET" style="margin-bottom: 15px;">
      <label for="lang"><?= $lang['language'] ?>:</label>
      <select name="lang" id="lang" onchange="this.form.submit()">
        <option value="en" <?= $language == 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
        <option value="fr" <?= $language == 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
        <option value="ro" <?= $language == 'ro' ? 'selected' : '' ?>>🇷🇴 Română</option>
        <option value="de" <?= $language == 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
        <option value="es" <?= $language == 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
        <option value="it" <?= $language == 'it' ? 'selected' : '' ?>>🇮🇹 Italiano</option>
        <option value="pt" <?= $language == 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
      </select>
    </form>

    <?php if (!empty($error_message)): ?>
      <p class="error"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="POST">
      <input type="hidden" name="language" value="<?= $language ?>">

      <label for="name"><?= $lang['full_name'] ?>:</label>
      <input type="text" name="name" id="name" required>

      <label for="email"><?= $lang['email'] ?>:</label>
      <input type="email" name="email" id="email" required>

      <label for="password"><?= $lang['password'] ?>:</label>
      <input type="password" name="password" id="password" required>

      <label for="age"><?= $lang['age'] ?>:</label>
      <input type="number" name="age" id="age" required>

      <label for="gender"><?= $lang['gender'] ?>:</label>
      <select name="gender" id="gender">
        <option value="Male"><?= $lang['male'] ?></option>
        <option value="Female"><?= $lang['female'] ?></option>
        <option value="Other"><?= $lang['other'] ?></option>
      </select>

      <label for="allergies"><?= $lang['allergies'] ?>:</label>
      <input type="text" name="allergies" id="allergies" required>

      <button type="submit" class="btn-register"><?= $lang['register'] ?></button>
    </form>

    <p><?= $lang['already_account'] ?> <a href="login.php"><?= $lang['login_here'] ?></a></p>
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

