<?php
// create_articles_json.php
// Tento skript vytvoří základní strukturu pro články na webu

// Kontrola existence složky data
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// Vytvoříme základní strukturu pro články
$articles = [
    // První ukázkový článek
    [
        'id' => 1,                      // Unikátní ID článku
        'name' => 'První článek',       // Název článku
        'content' => 'Obsah prvního článku...', // Obsah článku
        'created_at' => date('Y-m-d H:i:s'),    // Datum vytvoření
        'updated_at' => date('Y-m-d H:i:s'),    // Datum poslední úpravy
        'author' => 'admin'             // Autor článku
    ]
];

// Uložíme do souboru
if (file_put_contents('../data/articles.json', json_encode($articles, JSON_PRETTY_PRINT))) {
    echo "Soubor articles.json byl úspěšně vytvořen!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists('../data/articles.json')) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents('../data/articles.json'));
    echo "</pre>";
}
?>