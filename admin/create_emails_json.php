<?php
// ====== INICIALIZACE ======
// Skript pro vytvoření JSON souboru s emailovými šablonami a konfigurací

// ====== KONTROLA SLOŽKY ======
// Kontrola existence složky data, případně její vytvoření
if (!file_exists('data')) {
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// ====== DEFINICE STRUKTURY ======
// Základní struktura pro emaily a konfiguraci
$emails = [
    // Šablony emailů - pole pro uložení předpřipravených emailových šablon
    'templates' => [
        [
            'id' => 1,                           // Unikátní identifikátor šablony
            'email' => 'quest@example.com',      // Výchozí emailová adresa
            'name' => 'Info email',              // Název šablony pro administraci
            'subject' => 'Vítejte na webu',      // Předmět emailu
            'message' => 'Vážený/á {jmeno},\n\nVítejte na našem webu...'  // Text s placeholder proměnnou
        ]
    ],
    
    // Historie odeslaných emailů - prázdné pole pro budoucí záznamy
    'sent' => [],  
    
    // Nastavení SMTP serveru pro odesílání emailů
    'settings' => [
        'smtp_host' => 'smtp.example.com',       // Adresa SMTP serveru
        'smtp_port' => 587,                      // Port pro SMTP komunikaci
        'smtp_user' => 'your-email@example.com', // Uživatelské jméno pro SMTP
        // BEZPEČNOSTNÍ UPOZORNĚNÍ: Heslo by nemělo být přímo v JSON souboru!
        'smtp_pass' => 'extadmin'                // Heslo pro SMTP (v produkci použít bezpečnější řešení)
    ]
];

// ====== ULOŽENÍ DAT ======
// Pokus o uložení struktury do JSON souboru
// JSON_PRETTY_PRINT zajistí čitelné formátování výsledného souboru
if (file_put_contents('../data/emails.json', json_encode($emails, JSON_PRETTY_PRINT))) {
    echo "Soubor emails.json byl úspěšně vytvořen!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// ====== KONTROLA A VÝPIS ======
// Ověření vytvoření souboru a výpis jeho obsahu
if (file_exists('../data/emails.json')) {
    echo "<pre>";  // Formátovaný výpis
    echo htmlspecialchars(file_get_contents('../data/emails.json'));  // Bezpečný výpis obsahu
    echo "</pre>";
}
?>