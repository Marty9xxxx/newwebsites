<?php
// ====== KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku aktuálního adresáře
require_once dirname(__DIR__) . '/config.php';

// ====== DEFINICE CESTY ======
// Získání bezpečné cesty k JSON souboru pomocí pomocné funkce getFilePath
// Soubor bude uložen ve složce 'data' pod názvem 'guestbook.json'
$guestbook_file = getFilePath('data', 'guestbook.json');

// ====== VYTVOŘENÍ UKÁZKOVÝCH DAT ======
// Pole obsahující ukázkové příspěvky pro návštěvní knihu
$guestbook = [
    // První příspěvek - aktuální čas
    [
        'datetime' => date('Y-m-d H:i:s'),      // Aktuální datum a čas
        'author' => 'Admin',                     // Jméno autora
        'message' => 'Vítejte v naší nové návštěvní knize!' // Text zprávy
    ],
    // Druhý příspěvek - včerejší
    [
        'datetime' => date('Y-m-d H:i:s', strtotime('-1 day')), // Včerejší datum
        'author' => 'Petr',
        'message' => 'Skvělý web, jen tak dál!'
    ],
    // Třetí příspěvek - předvčerejší
    [
        'datetime' => date('Y-m-d H:i:s', strtotime('-2 days')), // Předvčerejší datum
        'author' => 'Jana',
        'message' => 'Moc se mi líbí design stránek.'
    ]
];

// ====== ULOŽENÍ DAT ======
// Pokus o uložení dat s ošetřením chyb pomocí try-catch bloku
try {
    // Kontrola existence složky data
    // dirname($guestbook_file) získá cestu ke složce ze souboru
    if (!is_dir(dirname($guestbook_file))) {
        // Vytvoření složky s právy 0777 (čtení/zápis/spouštění pro všechny)
        // true znamená rekurzivní vytvoření všech potřebných nadřazených složek
        mkdir(dirname($guestbook_file), 0777, true);
        echo "Složka data byla vytvořena!<br>";
    }
    
    // Uložení dat do JSON souboru
    // json_encode převede PHP pole na JSON řetězec
    // JSON_PRETTY_PRINT zajistí čitelné formátování výsledného JSON
    if (file_put_contents($guestbook_file, json_encode($guestbook, JSON_PRETTY_PRINT))) {
        echo "Soubor guestbook.json byl úspěšně vytvořen s ukázkovými daty!";
    } else {
        echo "Chyba: Nepodařilo se zapsat data do souboru!";
    }
} catch (Exception $e) {
    // Zachycení a výpis případné chyby
    echo "Chyba: " . $e->getMessage();
}
?>