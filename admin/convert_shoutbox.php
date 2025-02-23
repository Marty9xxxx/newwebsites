<?php
require_once '../config.php'; // Přidáno pro načtení funkce path

$shoutbox_file = path('data', 'shoutbox.txt');
$guestbook_file = path('data', 'guestbook.json');

if (file_exists($shoutbox_file)) {
    $data = file($shoutbox_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $guestbook = [];

    foreach ($data as $line) {
        // Rozdělení řádku na datum, čas, autora a zprávu
        if (preg_match('/^(\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}) \| (.+?): (.+)$/', $line, $matches)) {
            $datetime = $matches[1];
            $author = $matches[2];
            $message = $matches[3];

            $guestbook[] = [
                'datetime' => $datetime,
                'author' => $author,
                'message' => $message
            ];
        }
    }

    // Uložení dat do guestbook.json
    if (file_put_contents($guestbook_file, json_encode($guestbook, JSON_PRETTY_PRINT))) {
        echo "Soubor guestbook.json byl úspěšně vytvořen!";
    } else {
        echo "Nastala chyba při vytváření souboru guestbook.json!";
    }
} else {
    echo "Soubor shoutbox.txt neexistuje!";
}
?>