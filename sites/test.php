<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = "/../data/data.txt";

if (file_exists($file)) {
    echo "Soubor existuje!<br>";
    echo "Cesta k souboru: " . realpath($file) . "<br>";
    echo "Obsah souboru:<br>";
    echo nl2br(file_get_contents($file));
} else {
    echo "Soubor nenalezen!";
}
?>
<link rel="stylesheet" href="style1.css">
