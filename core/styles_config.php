<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Přístup zamítnut');
}

// Načtení dostupných stylů ze složky
$stylesDir = getFilePath('styles') . '/'; // nebo jakou máš cestu ke složce
$availableStyles = array_map(
    function($file) {
        return pathinfo($file, PATHINFO_FILENAME);
    },
    glob($stylesDir . '*.css')
);

$stylesConfig = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// Aktualizace JSONu s cestou a dostupnými styly
$stylesConfig['cssPath'] = $stylesDir;
$stylesConfig['availableStyles'] = $availableStyles;

// Uložení aktualizované konfigurace
file_put_contents(getFilePath('data', 'styles.json'), json_encode($stylesConfig, JSON_PRETTY_PRINT));