<?php
// Načtení konfigurace pro přístup k funkcím getFilePath
require_once dirname(__DIR__) . '/config.php';

// Definice cesty k JSON souboru
$guestbook_file = getFilePath('data', 'guestbook.json');

// Vytvoření ukázkových dat pro návštěvní knihu
$guestbook = [
    // První příspěvek
    [
        'datetime' => date('Y-m-d H:i:s'),
        'author' => 'Admin',
        'message' => 'Vítejte v naší nové návštěvní knize!'
    ],
    // Druhý příspěvek
    [
        'datetime' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'author' => 'Petr',
        'message' => 'Skvělý web, jen tak dál!'
    ],
    // Třetí příspěvek
    [
        'datetime' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'author' => 'Jana',
        'message' => 'Moc se mi líbí design stránek.'
    ]
];

// Pokus o uložení dat do JSON souboru
try {
    // Kontrola existence složky data
    if (!is_dir(dirname($guestbook_file))) {
        mkdir(dirname($guestbook_file), 0777, true);
        echo "Složka data byla vytvořena!<br>";
    }
    
    // Uložení dat do souboru s formátováním pro lepší čitelnost
    if (file_put_contents($guestbook_file, json_encode($guestbook, JSON_PRETTY_PRINT))) {
        echo "Soubor guestbook.json byl úspěšně vytvořen s ukázkovými daty!";
    } else {
        echo "Chyba: Nepodařilo se zapsat data do souboru!";
    }
} catch (Exception $e) {
    echo "Chyba: " . $e->getMessage();
}
?>