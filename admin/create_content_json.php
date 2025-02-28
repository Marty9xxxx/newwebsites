<?php
// Nastavení kódování pro výstup
header('Content-Type: text/html; charset=utf-8');

require_once dirname(__DIR__) . '/config.php';

// Kontrola existence složky data
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// Základní struktura pro obsah
$content = [
    'homepage' => [
        'title' => 'Vítejte na stránkách',
        'content' => 'Základní text úvodní stránky s českými znaky: ěščřžýáíé...',
        'last_edited' => date('Y-m-d H:i:s'),
        'edited_by' => 'admin'
    ]
];

// Definice cesty k souboru
$content_file = getFilePath('data', 'content.json');

// Uložení do JSON souboru s podporou českých znaků
file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Soubor content.json byl vytvořen!<br>";

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists($content_file)) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents($content_file));
    echo "</pre>";
}
?>