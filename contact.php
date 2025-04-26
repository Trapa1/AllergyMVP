<?php
session_start();

// Load language
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $to = "tabita.rapa100@gmail.com";
        $subject = $lang['new_message_subject'] . " $name";
        $email_content = $lang['name_label'] . ": $name\n";
        $email_content .= $lang['email_label'] . ": $email\n\n";
        $email_content .= $lang['message_label'] . ":\n$message\n";

        $headers = "From: $name <$email>";

        if (mail($to, $subject, $email_content, $headers)) {
            $success = $lang['success_message'];
        } else {
            $error = $lang['error_message'];
        }
    } else {
        $error = $lang['fill_all_fields'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['contact_us'] ?> | <?= $lang['app_name'] ?? 'Allergy Alert' ?></title>
    <link rel="stylesheet" href="css/design.css">
</head>
<body class="<?= $darkMode ? 'dark-mode' : '' ?>">



<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage !== 'index.php'): ?>
  <nav class="minimal-nav">
    <div class="minimal-container">
      <a href="index.php">← <?= $lang['home'] ?></a>
    </div>
  </nav>
<?php endif; ?>

<div class="register-container">
    <div class="register-box">
        <h1><?= $lang['contact_us'] ?></h1>

        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <label for="name"><?= $lang['your_name'] ?>:</label>
            <input type="text" name="name" required>

            <label for="email"><?= $lang['your_email'] ?>:</label>
            <input type="email" name="email" required>

            <label for="message"><?= $lang['your_message'] ?>:</label>
            <textarea name="message" rows="6" required></textarea>

            <button type="submit" class="btn-register"><?= $lang['send'] ?></button>
        </form>
    </div>
</div>

</body>
</html>
