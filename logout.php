<?php
session_start();

// Pokud je nastaven parametr 'logout', odhlásíme uživatele
if (isset($_GET['logout'])) {
    // Odhlášení: Smažeme session a přesměrujeme na login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
