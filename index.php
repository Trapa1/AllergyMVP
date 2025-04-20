<?php
session_start();

// Load language
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// Connect to SQLite
$db = new PDO('sqlite:database.sqlite');
$query = $db->query("SELECT * FROM medicines");
$medicines = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['app_name'] ?></title>
    <link rel="stylesheet" href="css/design.css">
    <script defer src="js/main.js"></script>
</head>
<body>

<nav>
    <div class="nav-container">
        <div class="logo"><?= $lang['app_name'] ?></div>
        <ul class="nav-links">
            <li><a href="index.php"><?= $lang['home'] ?></a></li>
            <li><a href="barcode_scanner.php"><?= $lang['scanner'] ?></a></li>
            <li><a href="faq.php"><?= $lang['faq'] ?></a></li>
            <li><a href="register.php"><?= $lang['register'] ?></a></li>
            <li><a href="login.php"><?= $lang['login'] ?></a></li>
            <li><a href="user_profile.php"><?= $lang['profile'] ?></a></li>
            <li><a href="history.php">📜 <?= $lang['scan_history'] ?></a></li>
        </ul>
        <div id="menu-toggle">☰</div>
    </div>
    <div id="mobile-menu">
        <a href="index.php"><?= $lang['home'] ?></a>
        <a href="faq.php"><?= $lang['faq'] ?></a>
        <a href="register.php"><?= $lang['register'] ?></a>
        <a href="login.php"><?= $lang['login'] ?></a>
        <a href="logout.php"><?= $lang['logout'] ?></a>
        <a href="history.php">📜 <?= $lang['scan_history'] ?></a>
    </div>
</nav>

<section class="hero">
    <a href="register.php" class="get-started-btn"><?= $lang['get_started'] ?></a>
    <img src="images/main image.jpg" alt="Main picture" class="full-width-image">
</section>

<section class="info">
    <h2><?= $lang['what_is'] ?></h2>
    <p><?= $lang['what_is_text1'] ?></p>
    <img src="images/meddicine photo.webp" alt="Medicine Image">
    <p><?= $lang['what_is_text2'] ?> 
        <a href="https://www.allergyuk.org/types-of-allergies/drug-allergy/" target="_blank"><?= $lang['read_more'] ?></a>.
    </p>
</section>

<section class="info">
    <h2><?= $lang['research_title'] ?></h2>
    <p><?= $lang['research_intro'] ?></p>
    <h3>📌 <?= $lang['mechanism'] ?></h3>
    <p><?= $lang['mechanism_text'] ?></p>
    <h3>📊 <?= $lang['statistics'] ?></h3>
    <p><?= $lang['statistics_text'] ?></p>
    <img src="images/allergies image.webp" alt="Allergy Research">
    <h3>🧪 <?= $lang['diagnostic'] ?></h3>
    <ul>
        <li>📍 <?= $lang['skin_testing'] ?></li>
        <li>📍 <?= $lang['blood_tests'] ?></li>
        <li>📍 <?= $lang['challenge_tests'] ?></li>
    </ul>
    <h3>🔬 <?= $lang['future'] ?></h3>
    <p><?= $lang['future_text'] ?></p>
    <p>
        <?= $lang['read_study'] ?>:
        <a href="https://www.ncbi.nlm.nih.gov/books/NBK447110/" target="_blank"><?= $lang['full_research'] ?></a>.
    </p>
</section>

<section class="info">
    <h2><?= $lang['spring_allergies_title'] ?></h2>
    <p><?= $lang['spring_intro'] ?></p>
    <img src="images/spring allergy.webp" alt="Spring Allergy">
    <h3>🌿 <?= $lang['causes'] ?></h3>
    <p><?= $lang['causes_text'] ?></p>
    <h3>🤧 <?= $lang['symptoms'] ?></h3>
    <ul>
        <li><?= $lang['sneeze'] ?></li>
        <li><?= $lang['itchy'] ?></li>
        <li><?= $lang['fatigue'] ?></li>
        <li><?= $lang['wheezing'] ?></li>
    </ul>
    <h3>✅ <?= $lang['prevention'] ?></h3>
    <ul>
        <li>✔ <?= $lang['purifier'] ?></li>
        <li>✔ <?= $lang['glasses'] ?></li>
        <li>✔ <?= $lang['antihistamine'] ?></li>
        <li>✔ <?= $lang['windows'] ?></li>
    </ul>
    <p>
        <?= $lang['read_spring'] ?>:
        <a href="https://www.webmd.com/allergies/spring-allergies" target="_blank"><?= $lang['read_more'] ?></a>.
    </p>
</section>

<section class="info">
    <h2><?= $lang['testing'] ?></h2>
    <p><?= $lang['testing_intro'] ?></p>
    <img src="images/testing.jpg" alt="Allergy Testing">
    <h3>🩺 <?= $lang['types_of_tests'] ?></h3>
    <ul>
        <li>📍 <?= $lang['skin_prick'] ?></li>
        <li>📍 <?= $lang['blood_test'] ?></li>
    </ul>
    <h3>📌 <?= $lang['why_get_tested'] ?></h3>
    <ul>
        <li>✔ <?= $lang['prevent_reactions'] ?></li>
        <li>✔ <?= $lang['get_treatment'] ?></li>
        <li>✔ <?= $lang['avoid_restrictions'] ?></li>
    </ul>
    <p>
        <?= $lang['read_testing'] ?>:
        <a href="https://www.allergylondon.com/allergy-testing/blood-tests/" target="_blank"><?= $lang['read_more'] ?></a>.
    </p>
</section>

<footer>
    <p>&copy; 2025 Allergy Alert | <?= $lang['footer'] ?></p>
</footer>

</body>
</html>

