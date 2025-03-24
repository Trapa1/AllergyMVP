
<?php
// Connect to SQLite database
$db = new PDO('sqlite:database.sqlite');

// Fetch medicines
$query = $db->query("SELECT * FROM medicines");
$medicines = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allergy Alert</title>
    <link rel="stylesheet" href="css/design.css"> <!-- Linking the CSS file -->
    <script defer src="js/main.js"></script> <!-- Linking JavaScript file -->
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div class="nav-container">
            <div class="logo">Allergy Alert</div>

            <!-- Desktop Menu -->
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="barcode_scanner.php">Scanner</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>

            <!-- Mobile Menu Toggle (Hamburger Icon) -->
            <div id="menu-toggle">☰</div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu">
            <a href="index.php">Home</a>
            <a href="barcode_scanner.php">Scanner</a>
            <a href="faq.php">FAQ</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <!-- <h1>Welcome to Allergy Alert</h1>
        <p>Your safety comes first. Scan medications before use to avoid allergic reactions.</p> -->
        <a href="register.php" class="get-started-btn">Get Started</a>
        </p>
        <img src="images/main image.jpg" alt="Main picture" class="full-width-image ">
        <p>
    </section>

    <!-- Drug Allergy Information -->
    <section class="info">
        <h2>What is a Drug Allergy?</h2>
        <p>
            A drug allergy is an immune system reaction to a medication. It is different from drug side effects or drug intolerance. 
            The immune system mistakenly identifies a drug as a harmful substance, triggering an allergic reaction.
        </p>
        <img src="images/meddicine photo.webp" alt="Medicine Image">
        <p>
            Common symptoms include rashes, nausea, dizziness, and in severe cases, anaphylaxis.
            Learn more about drug allergies, symptoms, and management. 
            <a href="https://www.allergyuk.org/types-of-allergies/drug-allergy/" target="_blank">Read more</a>.
        </p>
    </section>

    <!-- FULL Drug Allergy Research -->
    <section class="info">
        <h2>Drug Allergies Research</h2>
        <p>
            Drug allergies are a major concern in healthcare. The most common drug allergies involve antibiotics, non-steroidal anti-inflammatory drugs (NSAIDs), 
            and anesthesia medications.
        </p>
        <h3>📌 Understanding the Mechanism</h3>
        <p>
            When the immune system identifies a drug as harmful, it releases chemicals such as **histamines** and **cytokines**, 
            which cause inflammation and symptoms like **hives, swelling, and difficulty breathing**.
        </p>
        <h3>📊 Statistics on Drug Allergies</h3>
        <p>
            Studies show that **up to 10% of people report a drug allergy**, but only **10-20% of those cases** are confirmed by allergy testing. 
            This means many people may be avoiding medications unnecessarily.
        </p>
        <img src="images/allergies image.webp" alt="Allergy Research">
        <h3>🧪 Diagnostic Approaches</h3>
        <ul>
            <li>📍 **Skin Testing** - Used for penicillin and antibiotic allergies.</li>
            <li>📍 **Blood Tests** - Measures the body's immune response.</li>
            <li>📍 **Drug Challenge Tests** - Administering a small dose under supervision.</li>
        </ul>
        <h3>🔬 Future of Drug Allergy Treatment</h3>
        <p>
            New treatments like **desensitization therapy** are being developed to help people tolerate medications they are allergic to. 
            Ongoing research aims to reduce false diagnoses and improve allergy testing accuracy.
        </p>
        <p>
            Read the full **Drug Allergy Research** here:
            <a href="https://www.ncbi.nlm.nih.gov/books/NBK447110/" target="_blank">Full Research Study</a>.
        </p>
    </section>

    <!-- Spring Allergies -->
    <section class="info">
        <h2>Spring Allergies: Causes, Symptoms, and Prevention</h2>
        <p>
            Spring brings warmer temperatures, but also **pollen and allergens** that trigger seasonal allergies.
        </p>
        <img src="images/spring allergy.webp" alt="Spring Allergy">
        <h3>🌿 What Causes Spring Allergies?</h3>
        <p>
            The primary triggers are **pollen from trees, grass, and weeds**. The immune system mistakenly sees pollen as a threat, 
            leading to allergy symptoms.
        </p>
        <h3>🤧 Common Symptoms</h3>
        <ul>
            <li>Sneezing & runny nose</li>
            <li>Itchy, watery eyes</li>
            <li>Fatigue</li>
            <li>Wheezing (for asthma patients)</li>
        </ul>
        <h3>✅ Prevention Tips</h3>
        <ul>
            <li>✔ Use an air purifier indoors</li>
            <li>✔ Wear sunglasses to prevent pollen from entering eyes</li>
            <li>✔ Take antihistamines before allergy season</li>
            <li>✔ Keep windows closed during high pollen days</li>
        </ul>
        <p>
            Learn more about **Spring Allergies**:
            <a href="https://www.webmd.com/allergies/spring-allergies" target="_blank">Read More</a>.
        </p>
    </section>

    <!-- Allergy Testing -->
    <section class="info">
        <h2>The Importance of Allergy Testing</h2>
        <p>
            **Allergy testing is crucial** to determine which allergens affect you. It helps in diagnosis, treatment, and prevention of severe reactions.
        </p>
        <img src="images/testing.jpg" alt="Allergy Testing">
        <h3>🩺 Types of Allergy Tests</h3>
        <ul>
            <li>📍 **Skin Prick Test** - A small amount of allergen is applied to the skin.</li>
            <li>📍 **Blood Test** - Measures allergen-specific antibodies.</li>
        </ul>
        <h3>📌 Why Should You Get Tested?</h3>
        <ul>
            <li>✔ Prevent severe allergic reactions</li>
            <li>✔ Get the right treatment</li>
            <li>✔ Avoid unnecessary food/medicine restrictions</li>
        </ul>
        <p>
            Learn more about **Allergy Testing**:
            <a href="https://www.allergylondon.com/allergy-testing/blood-tests/" target="_blank">Read More</a>.
        </p>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Allergy Alert | Your Health, Your Safety</p>
    </footer>

</body>
</html>
