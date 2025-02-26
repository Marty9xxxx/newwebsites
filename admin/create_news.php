<?php
// Load the configuration   
require_once dirname(__DIR__) . '/config.php';

// Načtení dat ze souboru data.txt
$dataFile = getFilePath('data', 'data.txt');
$newsFile = GetFilePath('data', 'news.json');

if (file_exists($dataFile)) {
    $data = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $news = [];

    foreach ($data as $line) {
        list($author, $text, $datetime) = explode('|', $line);
        $news[] = [
            'author' => $author,
            'text' => $text,
            'datetime' => $datetime
        ];
    }

    // Uložení dat do news.json
    if (file_put_contents($newsFile, json_encode($news, JSON_PRETTY_PRINT))) {
        echo "Soubor news.json byl úspěšně vytvořen!";
    } else {
        echo "Nastala chyba při vytváření souboru news.json!";
    }
} else {
    echo "Soubor data.txt neexistuje!";
}
?>