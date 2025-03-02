<?php
// ====== KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku aktuálního adresáře
require_once dirname(__DIR__) . '/config.php';

// ====== DEFINICE CESTY ======
// Získání bezpečné cesty k JSON souboru pomocí pomocné funkce getFilePath
// Soubor bude uložen ve složce 'data' pod názvem 'styles.json'
$styles_file = getFilePath('data', 'styles.json');

// ====== DEFINICE STRUKTURY ======
// Vytvoření základní struktury pro správu stylů webu
$styles = [
    // Aktuálně používaný styl - výchozí hodnota
    'currentStyle' => 'style1',
    
    // Seznam dostupných CSS stylů
    'availableStyles' => [
        'style1',     // Výchozí hlavní styl webu
        'style2',     // Alternativní styl s širším menu
        'contrast'    // Vysoký kontrast pro lepší čitelnost
    ],
    
    // Relativní cesta ke složce obsahující CSS soubory
    // Používá se pro načítání stylů v šabloně
    'cssPath' => '/styles/'
];

// ====== ULOŽENÍ DAT ======
// Pokus o uložení dat s ošetřením chyb pomocí try-catch bloku
try {
    // Kontrola existence složky data
    // dirname($styles_file) získá cestu ke složce ze souboru
    if (!is_dir(dirname($styles_file))) {
        // Vytvoření složky s právy 0777 (čtení/zápis/spouštění pro všechny)
        // true znamená rekurzivní vytvoření všech potřebných nadřazených složek
        mkdir(dirname($styles_file), 0777, true);
        echo "Složka data byla vytvořena!<br>";
    }
    
    // Uložení dat do JSON souboru
    // json_encode převede PHP pole na JSON řetězec
    // JSON_PRETTY_PRINT zajistí čitelné formátování výsledného JSON
    if (file_put_contents($styles_file, json_encode($styles, JSON_PRETTY_PRINT))) {
        // Úspěšné vytvoření souboru
        echo "Soubor styles.json byl úspěšně vytvořen!<br>";
        // Výpis počtu dostupných stylů
        echo "Obsahuje " . count($styles['availableStyles']) . " dostupných stylů.";
    } else {
        // Chyba při zápisu do souboru
        echo "Chyba: Nepodařilo se zapsat data do souboru!";
    }
} catch (Exception $e) {
    // Zachycení a výpis případné chyby
    echo "Chyba: " . $e->getMessage();
}
?>