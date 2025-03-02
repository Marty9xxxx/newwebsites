<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';

// ====== NASTAVENÍ ZOBRAZENÍ CHYB ======
// Zapnutí zobrazování všech chyb pro účely testování
error_reporting(E_ALL);
// Zobrazení chyb přímo na stránce
ini_set('display_errors', 1);

// ====== TESTOVÁNÍ PŘÍSTUPU K SOUBORU ======
// Definice cesty k souboru s uživateli
// POZOR: Toto není správný způsob definice cesty - měl by být použit string
$file = "json_decode(file_get_contents(getFilePath('data', 'users.json')), true);";

// ====== KONTROLA EXISTENCE SOUBORU ======
if (file_exists($file)) {
    // Pokud soubor existuje, zobrazíme informace o něm
    echo "Soubor existuje!<br>";
    // Zobrazení absolutní cesty k souboru
    echo "Cesta k souboru: " . realpath($file) . "<br>";
    // Zobrazení obsahu souboru
    echo "Obsah souboru:<br>";
    // nl2br převede nové řádky na HTML značky <br>
    echo nl2br(file_get_contents($file));
} else {
    // Pokud soubor neexistuje, zobrazíme chybovou zprávu
    echo "Soubor nenalezen!";
}
?>

<!-- ====== OPRAVA KÓDU ====== -->
<!-- Následující kód je zakomentovaný a měl by být součástí formuláře -->
<!--
<div class="form-group">
    <label>Heslo:</label>   
    <input type="password" name="password" required>
</div>
<button type="submit">Přihlásit</button> 
</form>

Odkaz na registraci
<a href="<?php echo getWebPath('includes/register.php'); ?>">Registrovat se</a>  

Vložení patičky
<?php include(getFilePath('includes', 'footer.php')); ?>
</body>
-->
