<?php
// Vytvoření prvního JSON souboru pro styly

// Nejdřív zkontrolujeme/vytvoříme složku pro data
if (!file_exists('data')) {
    // Vytvoříme složku s právy 0755 (vlastník může číst/zapisovat/spouštět, ostatní jen číst/spouštět)
    mkdir('data', 0755);
}

// styles.json
$styles = [
    'theme' => 'light',
    'colors' => [
        'primary' => '#007bff',
        'secondary' => '#6c757d',
        'background' => '#ffffff'
    ],
    'fonts' => [
        'main' => 'Arial',
        'headings' => 'Roboto'
    ]
];

// Uložíme pole do JSON souboru
// json_encode převede PHP pole na JSON
// JSON_PRETTY_PRINT zajistí hezké formátování (odsazení)
$json_data = json_encode($styles, JSON_PRETTY_PRINT);

// Uložíme data do souboru
// file_put_contents zapíše obsah do souboru
// Pokud soubor neexistuje, vytvoří ho
file_put_contents('../data/styles.json', $json_data);

// Pro kontrolu můžeme vypsat obsah
echo "Soubor styles.json byl vytvořen!";

// Ukázka, jak později načíst data ze souboru:
$loaded_styles = json_decode(file_get_contents('../data/styles.json'), true);
?>