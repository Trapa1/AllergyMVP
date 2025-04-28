<?php
session_start();

// Load language
$language = $_SESSION['language'] ?? 'en';
$langFile = __DIR__ . "/language/$language.php";
$lang = file_exists($langFile) ? require $langFile : require __DIR__ . "/language/en.php";

// 🌑 Dark mode session check
$darkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === true;

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
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
</head>

<body class="<?= $darkMode ? 'dark-mode home' : 'home' ?>">


<!-- ✅ MAIN NAVIGATION -->
<nav class="main-navbar">
  <!-- Logo -->
  <div class="nav-logo">
    <a href="index.php">AllergyAlert</a>
  </div>

  <!-- Center Links -->
<div class="nav-center">
  <a href="index.php" class="nav-link"><?= $lang['home'] ?? 'Home' ?></a>
  <a href="barcode_scanner.php" class="scanner-button"><?= $lang['scanner'] ?? 'Scanner' ?></a>

  <div class="dropdown">
    <button class="dropbtn"><?= $lang['tools'] ?? 'Tools' ?> ▼</button>
    <div class="dropdown-content">
      <a href="user_profile.php"><?= $lang['my_profile'] ?? 'My Profile' ?></a>
      <a href="history.php"><?= $lang['my_history'] ?? 'My History' ?></a>
      <a href="settings.php"><?= $lang['settings'] ?? 'Settings' ?></a>
    </div>
  </div>

  <div class="dropdown">
    <button class="dropbtn"><?= $lang['learn'] ?? 'Learn' ?> ▼</button>
    <div class="dropdown-content">
      <a href="about.php"><?= $lang['about_us'] ?? 'About Us' ?></a>
      <a href="contact.php"><?= $lang['contact'] ?? 'Contact' ?></a>
      <a href="faq.php"><?= $lang['faq'] ?? 'FAQ' ?></a>
    </div>
  </div>
</div>

 <!-- Search Bar -->
<div class="nav-right">
  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="<?= $lang['search_placeholder'] ?? 'Search...' ?>" autocomplete="off">
    <button onclick="performSearch()">🔍</button>
    <div id="suggestions" class="suggestions-box"></div>
  </div>
</div>
    <div class="auth-links">
      <a href="register.php" class="btn-auth">Register</a>
      <a href="login.php" class="btn-auth">Login</a>
      <a href="logout.php" class="btn-auth">Logout</a>
    </div>
  </div>
</nav>

<!-- 🌟 HERO SECTION -->
<section class="hero-banner">
<a href="register.php" class="get-started-btn"><?= $lang['get_started'] ?></a>
  <img src="images/Allergy-Testing.png" alt="Allergy testing image" class="full-width-image" />
  <img src="images/qr-code.png" alt="QR Code" class="qr-code">
  <div class="hero-text-box">
  <h1><?= $lang['how_to_use_title'] ?></h1>
    <p>
     <?= $lang['how_to_use_step1'] ?><br>
     <?= $lang['how_to_use_step2'] ?><br>
     <?= $lang['how_to_use_step3'] ?><br>
     <?= $lang['how_to_use_step4'] ?><br><br>
      Because your health matters.
    </p>
  </div>
</section>


 <!-- ✅ IMAGE SECTION WITH BUTTON OVERLAY -->
 <section class="canvas-banner">
  <img src="images/second image.png" alt="Intro Banner" class="canvas-image">
  <a href="#articles" class="canvas-button"><?= $lang['explore_articles'] ?? 'Explore Articles ⟶' ?></a>
</section>

<!-- ✅ EDUCATIONAL ARTICLES GRID - CLEAN + RESPONSIVE -->
<section class="article-grid">
  <!-- ARTICLE CARD 1 -->
  <div class="article-card" id="pregnancy">
    <div class="date-badge">Thu 24 Apr 2025<br><span>from 9:00am</span></div>
    <img src="images/kids-allergies.jpeg" alt="Kids and Allergies">
    <div class="article-content">
      <h3><?= $lang['Allergies in Children'] ?></h3>
      <p><?= $lang['Children can develop allergic reactions to medicines early. Learn how to spot them and what steps to take.'] ?></p>
      <a href="https://www.henryford.com/blog/2024/05/how-to-handle-medication-allergies-in-kids" target="_blank"><?= $lang['read_more'] ?></a>
    </div>
  </div>

  <!-- ARTICLE CARD 2 -->
  <div class="article-card" id="spring">
    <div class="date-badge">Fri 25 Apr 2025<br><span>from 10:30am</span></div>
    <img src="images/medication-pregnancy.jpg" alt="Safe Medications in Pregnancy">
    <div class="article-content">
      <h3><?= $lang['Allergy Medications in Pregnancy'] ?></h3>
      <p><?= $lang['Not all allergy meds are safe during pregnancy. Discover which ones to use and what to avoid.'] ?></p>
      <a href="https://www.acog.org/womens-health/experts-and-stories/ask-acog/what-medicine-can-i-take-for-allergies-while-im-pregnant" target="_blank"><?= $lang['read_more'] ?></a>
    </div>
  </div>

  <!-- ARTICLE CARD 3 -->
  <div class="article-card" id="children">
    <div class="date-badge">Mon 28 Apr 2025<br><span>from 2:00pm</span></div>
    <img src="images/epi.jpg" alt="How to use an EpiPen">
    <div class="article-content">
      <h3><?= $lang['How to Use an EpiPen'] ?></h3>
      <p><?= $lang['Learn when and how to properly use an EpiPen — it can be life-saving during severe reactions.'] ?></p>
      <a href="https://www.allergyuk.org/resources/administering-an-epipen/" target="_blank"><?= $lang['read_more'] ?></a>
    </div>
  </div>

  <!-- ARTICLE CARD 4 -->
  <div class="article-card" id="epipen">
    <div class="date-badge">Tue 29 Apr 2025<br><span>from 4:15pm</span></div>
    <img src="images/preg.jpg" alt="Pregnancy Allergy Tips">
    <div class="article-content">
      <h3><?= $lang['Allergy Relief in Pregnancy'] ?></h3>
      <p><?= $lang['Simple lifestyle changes and safe remedies for managing allergies while pregnant.'] ?></p>
      <a href="https://allergyasthmanetwork.org/allergies/pregnancy-allergies/" target="_blank"><?= $lang['read_more'] ?></a>
    </div>
  </div>
</section>




<!-- ✅ DRUG ALLERGY -->
<section class="article-grid">
  <div class="article-card full-article">
    <div class="article-content">
      <h2><?= $lang['what_is'] ?></h2>
      <p><?= $lang['what_is_text1'] ?></p>
      <img src="images/meddicine photo.webp" alt="Medicine Image" class="info-image">
      <p><?= $lang['what_is_text2'] ?> 
        <a href="https://www.allergyuk.org/types-of-allergies/drug-allergy/" target="_blank"><?= $lang['read_more'] ?></a>.
      </p>
    </div>
  </div>

  <div class="article-card full-article">
    <div class="article-content">
      <h2><?= $lang['research_title'] ?></h2>
      <p><?= $lang['research_intro'] ?></p>
      <h3>📌 <?= $lang['mechanism'] ?></h3>
      <p><?= $lang['mechanism_text'] ?></p>
      <h3>📊 <?= $lang['statistics'] ?></h3>
      <p><?= $lang['statistics_text'] ?></p>
      <img src="images/allergies image.webp" alt="Allergy Research" class="info-image">
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
    </div>
  </div>


</section>

<!-- ✅ SPRING ALLERGIES SECTION -->

<section class="article-grid">
  <!-- other article cards... -->

  <!-- ✅ SPRING ALLERGIES SECTION -->
  <div class="article-card full-article">
    <div class="article-content">
      <h2><?= $lang['spring_allergies_title'] ?></h2>
      <p><?= $lang['spring_intro'] ?></p>
      <img src="images/spring allergy.webp" alt="Spring Allergy" class="info-image">
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
    </div>
  </div>
</section>
<script>
function handleSmartSearch(event) {
  event.preventDefault();
  const input = document.getElementById('smartSearchInput').value.toLowerCase().trim();

  if (!input) return false;

  // Direct match keywords
  if (input.includes("scanner")) window.location.href = "barcode_scanner.php";
  else if (input.includes("settings")) window.location.href = "settings.php";
  else if (input.includes("profile")) window.location.href = "user_profile.php";
  else if (input.includes("history")) window.location.href = "history.php";
  else if (input.includes("about")) window.location.href = "about.php";
  else if (input.includes("contact")) window.location.href = "contact.php";
  else if (input.includes("faq")) window.location.href = "faq.php";

  // Scroll to specific sections on index
  else if (input.includes("pregnancy") || input.includes("pregnancy allergies")) window.location.href = "index.php#pregnancy";
  else if (input.includes("spring") || input.includes("spring allergies")) window.location.href = "index.php#spring";
  else if (input.includes("children") || input.includes("kids")) window.location.href = "index.php#children";
  else if (input.includes("epipen")) window.location.href = "index.php#epipen";

  else {
    alert("No matching result. Try keywords like 'scanner', 'spring allergies', or 'settings'.");
  }

  return false;
}
</script>
<script>
// 📜 FULL Pages matching all menu links + important sections
const pages = {
  "<?= $lang['home'] ?? 'Home' ?>": "index.php",
  "<?= $lang['scanner'] ?? 'Scanner' ?>": "barcode_scanner.php",
  "<?= $lang['tools'] ?? 'Tools' ?>": "index.php#tools",
  "<?= $lang['learn'] ?? 'Learn' ?>": "index.php#learn",
  "<?= $lang['my_profile'] ?? 'My Profile' ?>": "user_profile.php",
  "<?= $lang['my_history'] ?? 'My History' ?>": "history.php",
  "<?= $lang['settings'] ?? 'Settings' ?>": "settings.php",
  "<?= $lang['about_us'] ?? 'About Us' ?>": "about.php",
  "<?= $lang['contact'] ?? 'Contact' ?>": "contact.php",
  "<?= $lang['faq'] ?? 'FAQ' ?>": "faq.php",
  "<?= $lang['register'] ?? 'Register' ?>": "register.php",
  "<?= $lang['login'] ?? 'Login' ?>": "login.php",
  "<?= $lang['logout'] ?? 'Logout' ?>": "logout.php",
  "<?= $lang['allergy_research'] ?? 'Allergy Research' ?>": "index.php#drugallergy",
  "<?= $lang['spring_allergies_title'] ?? 'Spring Allergies' ?>": "index.php#spring",
  "<?= $lang['Allergies in Children'] ?? 'Allergies in Children' ?>": "index.php#children",
  "<?= $lang['How to Use an EpiPen'] ?? 'How to Use an EpiPen' ?>": "index.php#epipen",
  "<?= $lang['safe_medications'] ?? 'Safe Medications' ?>": "index.php#pregnancy",
};

function performSearch() {
  const query = document.getElementById('searchInput').value.toLowerCase().trim();
  for (const key in pages) {
    if (key.toLowerCase().includes(query)) {
      window.location.href = pages[key];
      return;
    }
  }
  alert('No matching page found.');
}

// ✨ Autocomplete Suggestions
const searchInput = document.getElementById('searchInput');
const suggestionsBox = document.getElementById('suggestions');

searchInput.addEventListener('input', function() {
  const input = this.value.toLowerCase();
  suggestionsBox.innerHTML = '';
  if (input.length > 0) {
    const matches = Object.keys(pages).filter(p => p.toLowerCase().includes(input));
    matches.forEach(match => {
      const div = document.createElement('div');
      div.textContent = match;
      div.classList.add('suggestion-item');
      div.onclick = () => {
        searchInput.value = match;
        performSearch();
      };
      suggestionsBox.appendChild(div);
    });
  }
});
</script>

<style>
/* ✨ Suggestions Box Style */
.suggestions-box {
  position: absolute;
  background: white;
  width: 200px;
  max-height: 150px;
  overflow-y: auto;
  border: 1px solid #ccc;
  border-top: none;
  z-index: 1000;
  margin-top: -5px;
  box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
}

.suggestion-item {
  padding: 8px 10px;
  cursor: pointer;
}

.suggestion-item:hover {
  background-color: #f0f0f0;
}
</style>
</html>
 
<!-- ✅ FOOTER -->
<footer class="main-footer">
  <div class="footer-container">
    <p>© 2025 Allergy Alert – Your Safety, Our Priority</p>
    <div class="footer-links">
      <a href="terms.php">Terms & Cookies</a>
    </div>
  </div>
</footer>
</body>

