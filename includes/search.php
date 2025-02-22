<?php
// search.php
// Vyhledávání v souborech  ve složce
require_once './config.php';    

if (isset($_GET['q'])) {
    $query = strtolower(trim($_GET['q']));
    $directory = __DIR__; // Aktuální složka
    $results = [];

    // Prohledání všech souborů v hlavní složce
    foreach (glob($directory . "/*.php") as $file) {
        $content = file_get_contents($file);
        if (stripos($content, $query) !== false) {
            $filename = basename($file);
            $results[] = "<li><a href='$filename'>$filename</a></li>";
        }
    }

    if (!empty($results)) {
        echo '<h3>Výsledky hledání:</h3><ul>' . implode('', $results) . '</ul>';
    } else {
        echo 'Žádné výsledky nenalezeny.';
    }
}
