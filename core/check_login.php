<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ' . getWebPath('includes/login.php')");
    exit;
}

// Volitelné: Kontrola role admina
if ($_SESSION['role'] !== "admin") {
    echo "Nemáte oprávnění přistupovat do administrace!";
    exit;
}
?>
