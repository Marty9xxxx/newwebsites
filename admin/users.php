<?php
//users admin - správa uživatelů
session_start();
// Načtení dat z JSONů
$users = json_decode(file_get_contents(path('data', 'users.json')), true);
$styles = json_decode(file_get_contents(path('data', 'styles.json')), true);

// Vložení hlavičky
include(path('includes', 'header.php'));
