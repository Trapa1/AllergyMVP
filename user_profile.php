<?php
session_start();
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;

require 'vendor/autoload.php';

try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $user['name'] ?? '';
$age = $user['age'] ?? '';
$gender = $user['gender'] ?? '';
$allergies = $user['allergies'] ?? '';
$language = $user['language'] ?? 'en';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $allergies = strtolower(trim($_POST['allergies']));
    $language = $_POST['language'] ?? 'en';

    $_SESSION['language'] = $language;

    $stmt = $db->prepare("UPDATE users SET name = ?, age = ?, gender = ?, allergies = ?, language = ? WHERE id = ?");
    $stmt->execute([$name, $age, $gender, $allergies, $language, $user_id]);

    header("Location: user_profile.php?success=1");
    exit;
}

// Load NHS ingredient list for autocomplete
$allergyList = [];
$ingredientFile = __DIR__ . '/data/ingredient_names.txt';
if (file_exists($ingredientFile)) {
    $allergyList = file($ingredientFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $allergyList = array_map('strtolower', $allergyList);
    sort($allergyList);
}
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['profile'] ?></title>
    <link rel="stylesheet" href="css/design.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function () {
            var availableAllergies = <?= json_encode($allergyList) ?>;

            function split(val) {
                return val.split(/,\s*/);
            }

            function extractLast(term) {
                return split(term).pop();
            }

            $("#allergy-input")
                .on("keydown", function (event) {
                    if (event.keyCode === $.ui.keyCode.TAB &&
                        $(this).autocomplete("instance").menu.active) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    minLength: 1,
                    source: function (request, response) {
                        response($.ui.autocomplete.filter(
                            availableAllergies, extractLast(request.term)
                        ));
                    },
                    focus: function () {
                        return false;
                    },
                    select: function (event, ui) {
                        var terms = split(this.value);
                        terms.pop();
                        terms.push(ui.item.value);
                        terms.push("");
                        this.value = terms.join(", ");
                        return false;
                    }
                });
        });
    </script>
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


<section class="profile-container">
  <div class="profile-box">
    <h1>👤 <?= $lang['update_profile'] ?></h1>

    <?php if (isset($_GET['success'])): ?>
      <p class="success">✅ <?= $lang['profile_updated'] ?></p>
    <?php endif; ?>

    <form method="POST">
        <label><?= $lang['name'] ?>: <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required></label>
        <label><?= $lang['age'] ?>: <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required></label>
        <label><?= $lang['gender'] ?>:
            <select name="gender">
                <option value="Male" <?= ($gender == "Male") ? "selected" : "" ?>><?= $lang['male'] ?></option>
                <option value="Female" <?= ($gender == "Female") ? "selected" : "" ?>><?= $lang['female'] ?></option>
                <option value="Other" <?= ($gender == "Other") ? "selected" : "" ?>><?= $lang['other'] ?></option>
            </select>
        </label>
        <label><?= $lang['allergies'] ?>:
            <input type="text" id="allergy-input" name="allergies" value="<?= htmlspecialchars($allergies) ?>" required>
        </label>
        <label><?= $lang['language'] ?>:
            <select name="language" required>
                <option value="en" <?= $language == 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                <option value="fr" <?= $language == 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
                <option value="ro" <?= $language == 'ro' ? 'selected' : '' ?>>🇷🇴 Română</option>
                <option value="de" <?= $language == 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                <option value="es" <?= $language == 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
                <option value="it" <?= $language == 'it' ? 'selected' : '' ?>>🇮🇹 Italiano</option>
                <option value="pt" <?= $language == 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
            </select>
        </label>
        <button type="submit"><?= $lang['save'] ?></button>
    </form>
  </div>
</section>

</body>
</html>



