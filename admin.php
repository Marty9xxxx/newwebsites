<?php
session_start(); // session_start() by mělo být na začátku každého souboru, kde pracuješ se session

// Připojení k databázi
require "db.php"; // Ujisti se, že tento soubor obsahuje správné připojení k databázi pomocí PDO

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

// Načtení aktuálního stylu z databáze
$stmt = $pdo->prepare("SELECT hodnota FROM nastaveni WHERE klic = 'styl'");
$stmt->execute();
$aktualniStyl = $stmt->fetchColumn() ?: "style1.css";

// Pokud je odeslán formulář, aktualizujeme styl v DB
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["style"])) {
    $stmt = $pdo->prepare("UPDATE nastaveni SET hodnota = ? WHERE klic = 'styl'");
    $stmt->execute([$_POST["style"]]);
    $aktualniStyl = $_POST["style"];
}

// Zahrnutí hlavičky
include 'header.php';
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <!-- Dynamické načtení stylu -->
    <link rel="stylesheet" href="styles/<?= htmlspecialchars($aktualniStyl) ?>">
</head>
<body>
    <h2>Administrace</h2>
    <p>Zde můžete spravovat obsah webu.</p>

    <h2>Vyberte styl</h2>
    <form method="post">
        <label>
            <input type="radio" name="style" value="style1.css" <?= $aktualniStyl == "style1.css" ? "checked" : "" ?>>
            Světlý styl
        </label><br>
        <label>
            <input type="radio" name="style" value="style2.css" <?= $aktualniStyl == "style2.css" ? "checked" : "" ?>>
            Tmavý styl
        </label><br>
        <button type="submit">Uložit</button>
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>
