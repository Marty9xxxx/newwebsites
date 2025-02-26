<?php
// logout.php
session_start();

// Load the configuration   
require_once dirname(__DIR__) . '/config.php';

// Před odhlášením si uložíme zprávu pro uživatele
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Zrušíme všechny proměnné v session
$_SESSION = array();

// Zrušíme session cookie
if (isset($_COOKIE[session_name()])) {
    // Důležité: nastavíme všechny parametry cookie stejně jako při vytvoření
    setcookie(session_name(), '', time()-3600, '/', '', true, true);
}

// Zrušíme všechny ostatní cookies spojené s aplikací
foreach ($_COOKIE as $name => $value) {
    setcookie($name, '', time()-3600, '/');
}

// Zničíme session
session_destroy();

// Přidáme hlavičky proti cachování
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Přesměrujeme s parametrem pro kontrolu
header("Location: login.php?logged_out=true");
exit;
