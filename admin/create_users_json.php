<?php
// ====== INICIALIZACE ======
// Nastavení kódování pro správné zobrazení češtiny
header('Content-Type: text/html; charset=utf-8');

// ====== KONTROLA SLOŽKY ======
// Nejdřív zkontrolujeme/vytvoříme složku data
if (!file_exists('data')) {
    // Vytvoříme složku data s právy pro čtení/zápis
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// ====== DEFINICE UŽIVATELŮ ======
// Vytvoříme pole s výchozími uživateli
$users = [
    // První uživatel - běžný účet
    [
        'id' => 1,                              // Unikátní identifikátor
        'username' => 'Martin',                 // Přihlašovací jméno
        'password' => password_hash('martin', PASSWORD_DEFAULT),  // Hashované heslo
        'role' => 'user',                       // Role uživatele (běžný uživatel)
        'email' => 'martin@example.com',        // Kontaktní email
        'created_at' => date('Y-m-d H:i:s')    // Datum vytvoření účtu
    ],
    // Druhý uživatel - administrátor
    [
        'id' => 2,                              // Unikátní identifikátor
        'username' => 'Marty9xxxx',             // Přihlašovací jméno
        'password' => password_hash('Marty9', PASSWORD_DEFAULT),  // Hashované heslo
        'role' => 'admin',                      // Role uživatele (administrátor)
        'email' => 'admin@example.com',         // Kontaktní email
        'created_at' => date('Y-m-d H:i:s')    // Datum vytvoření účtu
    ]
];

// ====== ULOŽENÍ DAT ======
// Pokus o uložení pole uživatelů do JSON souboru
// JSON_PRETTY_PRINT zajistí čitelné formátování výstupu
if (file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT))) {
    echo "Soubor users.json byl úspěšně vytvořen!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// ====== KONTROLA A VÝPIS ======
// Pro kontrolu přečteme a vypíšeme obsah (bezpečně)
if (file_exists('../data/users.json')) {
    echo "<pre>";  // Formátovaný výstup
    // htmlspecialchars zajistí bezpečný výpis dat
    echo htmlspecialchars(file_get_contents('../data/users.json'));
    echo "</pre>";
}
?>