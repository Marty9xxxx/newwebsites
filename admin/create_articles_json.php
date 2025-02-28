<?php
// Nastavení kódování pro výstup
header('Content-Type: text/html; charset=utf-8');

require_once dirname(__DIR__) . '/config.php';

// Kontrola existence složky data
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// Základní struktura pro články
$articles = [
    'articles' => [
        [
            'id' => 1,
            'title' => 'První článek',
            'perex' => 'Ukázkový perex prvního článku',
            'content' => 'Obsah prvního článku...',
            'author' => 'admin',
            'datetime' => date('Y-m-d H:i:s'),
            'published' => true
        ]
    ]
];

// Definice cesty k souboru pomocí getFilePath
$articles_file = getFilePath('data', 'articles.json');

// Uložení do JSON souboru s podporou českých znaků
file_put_contents($articles_file, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Soubor articles.json byl vytvořen!<br>";

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists($articles_file)) {  // Použití proměnné s cestou
    echo "<pre>";
    echo htmlspecialchars(file_get_contents($articles_file));  // Použití proměnné s cestou
    echo "</pre>";
}
?>