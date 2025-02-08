<?php include 'header.php'; ?>
<h1>Hledaná stránka...</h1>
<p>...Nebyla nalezena, zkuste zadat přesný název, případně využijte hledání na webu. Máte-li pocit, že se jedná o chybu, kontaktujte svého správce webu, případně kontaktujte nás přes <a href="contact.php" target="_blank">email</a></p>
<link rel="stylesheet" href="<?= htmlspecialchars($aktualniStyl); ?>">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = "/data/data.txt";

if (file_exists($file)) {
    echo "Soubor existuje!<br>";
    echo "Obsah souboru: " . file_get_contents($file);
} else {
    echo "Soubor nenalezen!";
}

?>
<?php include 'footer.php'; ?>