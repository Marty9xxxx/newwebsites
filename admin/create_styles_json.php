<?php
// Načtení konfigurace pro přístup k funkcím getFilePath
require_once dirname(__DIR__) . '/config.php';

// Definice cesty k JSON souboru
$styles_file = getFilePath('data', 'styles.json');

// Vytvoření základní struktury pro styly
$styles = [
    // Aktuálně používaný styl
    'currentStyle' => 'style1',
    
    // Seznam dostupných stylů
    'availableStyles' => [
        'style1',  // Výchozí hlavní styl
        'style2',     // Širší horní menu
        'contrast'     // Vysoký kontrast barev i rozložení ;)
    ],
    
    // Cesta ke složce se styly
    'cssPath' => '/styles/'
];

// Pokus o uložení dat do JSON souboru
try {
    // Kontrola existence složky data
    if (!is_dir(dirname($styles_file))) {
        mkdir(dirname($styles_file), 0777, true);
        echo "Složka data byla vytvořena!<br>";
    }
    
    // Uložení dat do souboru s formátováním pro lepší čitelnost
    if (file_put_contents($styles_file, json_encode($styles, JSON_PRETTY_PRINT))) {
        echo "Soubor styles.json byl úspěšně vytvořen!<br>";
        echo "Obsahuje " . count($styles['availableStyles']) . " dostupných stylů.";
    } else {
        echo "Chyba: Nepodařilo se zapsat data do souboru!";
    }
} catch (Exception $e) {
    echo "Chyba: " . $e->getMessage();
}
?>