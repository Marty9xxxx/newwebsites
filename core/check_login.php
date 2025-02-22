<?php
session_start();
require_once './config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Volitelné: Kontrola role admina
if ($_SESSION['role'] !== "admin") {
    echo "Nemáte oprávnění přistupovat do administrace!";
    exit;
}
?>
