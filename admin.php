<?php 
session_start();
include 'header.php';


// Přihlášení do administrace
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    if ($_POST['password'] == 'tajneheslo') {
        $_SESSION['admin'] = true;
    } else {
        echo "<p style='color:red;'>Chybné heslo!</p>";
    }
}

// Pokud není uživatel přihlášen, zobrazí se formulář
if (!isset($_SESSION['admin'])) {
    echo '<form method="post" action="admin.php">
            <label for="password">Heslo:</label>
            <input type="password" name="password">
            <button type="submit">Přihlásit</button>
          </form>';
    include 'footer.php';
    exit;
}

// Načteme aktuální styl z databáze
$stmt = $pdo->prepare("SELECT hodnota FROM nastaveni WHERE klic = 'styl'");
$stmt->execute();
$aktualniStyl = $stmt->fetchColumn() ?: "style1.css";

// Pokud je odeslán formulář, aktualizujeme styl v DB
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["style"])) {
    $stmt = $pdo->prepare("UPDATE nastaveni SET hodnota = ? WHERE klic = 'styl'");
    $stmt->execute([$_POST["style"]]);
    $aktualniStyl = $_POST["style"];
}
?>
<link rel="stylesheet" href="<?= htmlspecialchars($aktualniStyl); ?>">
<h2>Administrace</h2>
<p>Tady můžete spravovat obsah webu.</p>

<h2>Vyber styl</h2>
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

