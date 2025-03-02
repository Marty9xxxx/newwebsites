<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením uživatele
// Musí být před jakýmkoliv výstupem
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
// Ověření, zda existuje session s uživatelským jménem
// Pokud ne, přesměrování na přihlašovací stránku
if (!isset($_SESSION['username'])) {
    header("Location: " . getWebPath('includes/login.php'));
    // Ukončení skriptu pro zabránění dalšího vykonávání
    exit;
}

// ====== KONTROLA OPRÁVNĚNÍ ======
// Ověření, zda má uživatel roli administrátora
// Pokud ne, zobrazení chybové hlášky a ukončení skriptu
if ($_SESSION['role'] !== "admin") {
    // Výpis chybové hlášky
    echo "Nemáte oprávnění přistupovat do administrace!";
    // Ukončení skriptu
    exit;
}

// ====== BEZPEČNOSTNÍ KONTROLY ======
// Kontrola platnosti session (volitelné)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Pokud je session starší než 30 minut
    session_unset();     // Odstranění všech proměnných
    session_destroy();   // Zrušení session
    header("Location: " . getWebPath('includes/login.php'));
    exit;
}

// Aktualizace času poslední aktivity
$_SESSION['last_activity'] = time();
?>
