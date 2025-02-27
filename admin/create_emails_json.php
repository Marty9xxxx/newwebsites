<?php
// create_emails_json.php

// Kontrola/vytvoření složky
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// Základní struktura pro emaily
$emails = [
    'templates' => [
        [
            'id' => 1,
            'email' => 'quest@example.com',  // email přesunut do samostatného pole
            'name' => 'Info email',
            'subject' => 'Vítejte na webu',
            'message' => 'Vážený/á {jmeno},\n\nVítejte na našem webu...'
        ]
    ],
    'sent' => [],  // Historie odeslaných emailů
    'settings' => [
        'smtp_host' => 'smtp.example.com',
        'smtp_port' => 587,
        'smtp_user' => 'your-email@example.com',
        // Heslo by mělo být v bezpečnější konfiguraci, ne přímo v JSONu!
        'smtp_pass' => 'extadmin'  
    ]
];

// Uložíme do souboru
if (file_put_contents('../data/emails.json', json_encode($emails, JSON_PRETTY_PRINT))) {
    echo "Soubor emails.json byl úspěšně vytvořen!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// Kontrolní výpis
if (file_exists('../data/emails.json')) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents('../data/emails.json'));
    echo "</pre>";
}
?>