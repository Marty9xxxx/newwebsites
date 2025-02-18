<?php
// create_users_json.php

// Nejdřív zkontrolujeme/vytvoříme složku data
if (!file_exists('data')) {
    // Vytvoříme složku data
    mkdir('data');
    echo "Složka data byla vytvořena!<br>";
}

// Vytvoříme pole s výchozími uživateli
$users = [
    // První uživatel - běžný účet
    [
        'id' => 1,
        'username' => 'Martin',
        'password' => password_hash('martin', PASSWORD_DEFAULT),
        'role' => 'user',
        'email' => 'martin@example.com',
        'created_at' => date('Y-m-d H:i:s')
    ],
    // Druhý uživatel - administrátor
    [
        'id' => 2,
        'username' => 'Marty9xxxx',
        'password' => password_hash('Marty9', PASSWORD_DEFAULT),
        'role' => 'admin',
        'email' => 'admin@example.com',
        'created_at' => date('Y-m-d H:i:s')
    ]
];

// Uložíme do souboru
if (file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT))) {
    echo "Soubor users.json byl úspěšně vytvořen!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists('../data/users.json')) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents('../data/users.json'));
    echo "</pre>";
}
?>