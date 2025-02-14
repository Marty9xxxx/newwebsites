<?php
session_start(); // Načteme session

// Pokud je parametre 'logout' v URL, odhlásíme uživatele
if (isset($_GET['logout'])) {
    // Odstraníme všechny proměnné session
    session_unset();
    
    // Ukončíme session
    session_destroy();
    
    // Odstraníme session cookie (pokud nějaké existuje)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, 
            $params["path"], 
            $params["domain"], 
            $params["secure"], 
            $params["httponly"]
        );
    }
    
    // Přesměrování na stránku přihlášení
    header("Location: login.php");
    exit; // Ukončení skriptu
}
?>
