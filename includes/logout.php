<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro přístup k session proměnným
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== ULOŽENÍ INFORMACÍ PRO ZPĚTNOU VAZBU ======
// Uložíme si jméno uživatele pro pozdější zobrazení zprávy
// Použijeme ternární operátor pro kontrolu existence proměnné
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// ====== VYČIŠTĚNÍ SESSION ======
// Vyprázdnění pole $_SESSION - odstraní všechny session proměnné
$_SESSION = array();

// ====== ODSTRANĚNÍ SESSION COOKIE ======
// Kontrola existence session cookie
if (isset($_COOKIE[session_name()])) {
    // Nastavení cookie s prošlým časem pro její odstranění
    // Parametry:
    // 1. Název cookie (zde název session)
    // 2. Prázdná hodnota
    // 3. Čas vypršení (1 hodina v minulosti)
    // 4. Cesta ('/' pro celý web)
    // 5. Secure flag (true pro HTTPS only)
    // 6. HttpOnly flag (true pro zamezení přístupu přes JavaScript)
    setcookie(session_name(), '', time()-3600, '/', '', true, true);
}

// ====== ODSTRANĚNÍ OSTATNÍCH COOKIES ======
// Projdeme všechny cookies a odstraníme je
foreach ($_COOKIE as $name => $value) {
    // Nastavení cookie s prošlým časem
    setcookie($name, '', time()-3600, '/');
}

// ====== ZRUŠENÍ SESSION ======
// Kompletní odstranění session dat ze serveru
session_destroy();

// ====== ZABEZPEČENÍ PROTI CACHOVÁNÍ ======
// Nastavení HTTP hlaviček pro zamezení ukládání do cache
// no-store: Prohlížeč nesmí ukládat odpověď
// no-cache: Musí vždy ověřit obsah se serverem
// must-revalidate: Musí ověřit stav cache se serverem
// max-age=0: Cache okamžitě expiruje
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// Starší prohlížeče
header("Pragma: no-cache");

// ====== PŘESMĚROVÁNÍ ======
// Přesměrování na přihlašovací stránku s parametrem pro zobrazení zprávy
header("Location: login.php?logged_out=true");
// Ukončení skriptu pro zajištění přesměrování
exit;
