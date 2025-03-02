<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘÍSTUPU ======
// Ověření, zda je uživatel přihlášen jako admin
if (!isset($_SESSION['admin_logged_in'])) {
    die('Přístup zamítnut');
}

// ====== NAČTENÍ DOSTUPNÝCH STYLŮ ======
// Získání cesty ke složce se styly
$stylesDir = getFilePath('styles') . '/';

// Načtení všech CSS souborů ze složky
// glob() najde všechny soubory s příponou .css
// array_map() transformuje pole nalezených souborů
$availableStyles = array_map(
    // Anonymní funkce pro získání názvu souboru bez přípony
    function($file) {
        return pathinfo($file, PATHINFO_FILENAME);
    },
    // Hledání všech CSS souborů ve složce
    glob($stylesDir . '*.css')
);

// ====== NAČTENÍ EXISTUJÍCÍ KONFIGURACE ======
// Načtení současného JSON souboru s konfigurací stylů
// true jako druhý parametr převede JSON na asociativní pole
$stylesConfig = json_decode(
    file_get_contents(getFilePath('data', 'styles.json')), 
    true
);

// ====== AKTUALIZACE KONFIGURACE ======
// Uložení cesty ke složce se styly
$stylesConfig['cssPath'] = $stylesDir;
// Uložení seznamu dostupných stylů
$stylesConfig['availableStyles'] = $availableStyles;

// ====== ULOŽENÍ KONFIGURACE ======
// Uložení aktualizované konfigurace do JSON souboru
// JSON_PRETTY_PRINT zajistí čitelné formátování
file_put_contents(
    getFilePath('data', 'styles.json'), 
    json_encode($stylesConfig, JSON_PRETTY_PRINT)
);

// ====== POTVRZENÍ AKTUALIZACE ======
echo "Konfigurace stylů byla úspěšně aktualizována.<br>";
echo "Nalezeno " . count($availableStyles) . " dostupných stylů.";
?>