<?php
require_once dirname(__DIR__) . '/config.php';
//users admin - správa uživatelů
session_start();
// Načtení dat z JSONů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// Vložení hlavičky
include(getFilePath('includes', 'header.php'));
