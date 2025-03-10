<?php
header('Content-Type: application/json');

// Predefined list of common allergies (you can replace this with a database query later)
$allergies = [
    "Penicillin", "Amoxicillin", "Cefalexin", "Erythromycin", "Azithromycin",
    "Ibuprofen", "Aspirin", "Naproxen", "Diclofenac", "Celecoxib",
    "Codeine", "Morphine", "Tramadol", "Fentanyl", "Oxycodone",
    "Sulfa drugs", "Tetracycline", "Doxycycline", "Minocycline",
    "Metronidazole", "Fluoroquinolones", "Levofloxacin", "Ciprofloxacin",
    "Beta-blockers", "ACE inhibitors", "Statins", "Anticonvulsants",
    "Insulin", "Heparin", "Warfarin", "Carbamazepine", "Phenytoin",
    "Methotrexate", "Chloramphenicol", "Rifampin", "Vancomycin",
    "Benzodiazepines", "Diazepam", "Lorazepam", "Alprazolam"
];

// Get the user's input query
$query = isset($_GET['query']) ? strtolower($_GET['query']) : "";

// Filter allergies that match the query
$suggestions = array_filter($allergies, function ($allergy) use ($query) {
    return strpos(strtolower($allergy), $query) !== false;
});

// Return JSON response
echo json_encode(array_values($suggestions));
?>

