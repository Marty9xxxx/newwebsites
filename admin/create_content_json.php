<?php
// ====== NASTAVENÍ KÓDOVÁNÍ ======
// Nastavení HTTP hlavičky pro správné zobrazení českých znaků v prohlížeči
header('Content-Type: text/html; charset=utf-8');

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi (např. getFilePath)
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA SLOŽKY ======
// Ověření existence složky data, případně její vytvoření
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// ====== DEFINICE OBSAHU ======
// Vytvoření základní struktury pro obsah webu
$content = [
    // Sekce pro úvodní stránku
    'homepage' => [
        'title' => 'Vítejte na stránkách',          // Nadpis úvodní stránky
        'content' => 'Základní text úvodní stránky s českými znaky: ěščřžýáíé...', // Obsah s diakritikou
        'last_edited' => date('Y-m-d H:i:s'),       // Datum poslední úpravy
        'edited_by' => 'admin'                       // Kdo provedl poslední úpravu
    ]
];

// ====== CESTA K SOUBORU ======
// Získání bezpečné cesty k JSON souboru pomocí pomocné funkce
$content_file = getFilePath('data', 'content.json');

// ====== ULOŽENÍ DAT ======
// Uložení struktury do JSON souboru
// JSON_PRETTY_PRINT - zajistí čitelné formátování JSON
// JSON_UNESCAPED_UNICODE - zachová české znaky bez escapování
file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Soubor content.json byl vytvořen!<br>";

// ====== KONTROLA A VÝPIS ======
// Ověření vytvoření souboru a výpis jeho obsahu pro kontrolu
if (file_exists($content_file)) {
    echo "<pre>";  // Předformátovaný výstup pro lepší čitelnost
    // htmlspecialchars - ochrana proti XSS útokům při výpisu
    echo htmlspecialchars(file_get_contents($content_file));
    echo "</pre>";
}
?>