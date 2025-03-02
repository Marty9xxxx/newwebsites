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

// ====== DEFINICE STRUKTURY ======
// Vytvoření základní struktury pro články
$articles = [
    'articles' => [  // Hlavní pole obsahující všechny články
        [
            'id' => 1,                                    // Unikátní identifikátor článku
            'title' => 'První článek',                    // Nadpis článku
            'perex' => 'Ukázkový perex prvního článku',   // Krátký popis/úvod článku
            'content' => 'Obsah prvního článku...',       // Hlavní obsah článku
            'author' => 'admin',                          // Autor článku
            'datetime' => date('Y-m-d H:i:s'),           // Datum a čas vytvoření
            'published' => true                           // Stav publikování (true = publikováno)
        ]
    ]
];

// ====== CESTA K SOUBORU ======
// Získání bezpečné cesty k JSON souboru pomocí pomocné funkce
$articles_file = getFilePath('data', 'articles.json');

// ====== ULOŽENÍ DAT ======
// Uložení struktury do JSON souboru
// JSON_PRETTY_PRINT - zajistí čitelné formátování JSON
// JSON_UNESCAPED_UNICODE - zachová české znaky bez escapování
file_put_contents($articles_file, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Soubor articles.json byl vytvořen!<br>";

// ====== KONTROLA A VÝPIS ======
// Ověření vytvoření souboru a výpis jeho obsahu pro kontrolu
if (file_exists($articles_file)) {
    echo "<pre>";  // Předformátovaný výstup pro lepší čitelnost
    // htmlspecialchars - ochrana proti XSS útokům při výpisu
    echo htmlspecialchars(file_get_contents($articles_file));
    echo "</pre>";
}
?>