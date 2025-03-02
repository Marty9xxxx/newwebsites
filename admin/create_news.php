<?php
// ====== KONFIGURACE ======
// Načtení konfiguračního souboru pro přístup k funkcím getFilePath
require_once dirname(__DIR__) . '/config.php';

// ====== DEFINICE CEST K SOUBORŮM ======
// Cesta k vstupnímu souboru data.txt obsahujícímu novinky
$dataFile = getFilePath('data', 'data.txt');
// Cesta k výstupnímu JSON souboru pro novinky
$newsFile = GetFilePath('data', 'news.json');

// ====== ZPRACOVÁNÍ DAT ======
// Kontrola existence vstupního souboru
if (file_exists($dataFile)) {
    // Načtení řádků ze souboru do pole
    // FILE_IGNORE_NEW_LINES - odstraní znaky konce řádku
    // FILE_SKIP_EMPTY_LINES - přeskočí prázdné řádky
    $data = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Inicializace pole pro novinky
    $news = [];

    // Zpracování každého řádku
    foreach ($data as $line) {
        // Rozdělení řádku podle oddělovače |
        // Očekávaný formát: autor|text|datum
        list($author, $text, $datetime) = explode('|', $line);
        
        // Přidání nové novinky do pole
        // Vytvoření asociativního pole s klíči author, text a datetime
        $news[] = [
            'author' => $author,    // Autor novinky
            'text' => $text,        // Text novinky
            'datetime' => $datetime  // Datum a čas vytvoření
        ];
    }

    // ====== ULOŽENÍ DO JSON ======
    // Pokus o uložení pole novinek do JSON souboru
    // JSON_PRETTY_PRINT zajistí čitelné formátování JSON výstupu
    if (file_put_contents($newsFile, json_encode($news, JSON_PRETTY_PRINT))) {
        echo "Soubor news.json byl úspěšně vytvořen!";
    } else {
        // Chybová hláška při neúspěšném zápisu
        echo "Nastala chyba při vytváření souboru news.json!";
    }
} else {
    // Chybová hláška pokud vstupní soubor neexistuje
    echo "Soubor data.txt neexistuje!";
}
?>