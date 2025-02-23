<?php
require_once dirname(__DIR__) . '/config.php'; // Absolutní cesta pro načtení funkce path
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Svatoušek</title>
    <link rel="stylesheet" href="<?php echo path('styles', 'styles.css'); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo path('styles', 'print.css'); ?>" media="print">
</head>
<header>
    <h1><a href="<?php echo path('', 'index.php'); ?>">Svatoušek</a></h1>
    <nav>
        <ul>
            <li><a href="<?php echo path('admin', 'login.php'); ?>">Přihlášení</a></li>
            <li><a href="<?php echo path('admin', 'logout.php'); ?>">Odhlásit se</a></li>
            <li><a href="<?php echo path('admin', 'register.php'); ?>">Registrace</a></li>
            <li><a href="<?php echo path('', 'index.php'); ?>">Úvod</a></li>
            <li><a href="<?php echo path('', 'contact.php'); ?>">Napište mi</a></li>
            <li><a href="<?php echo path('admin', 'news.php'); ?>">Novinky</a></li>
            <li><a href="<?php echo path('admin', 'admin.php'); ?>">Administrace</a></li>
            <li><a href="<?php echo path('errors', 'error404.php'); ?>">Chyba!</a></li>
            <li><a href="<?php echo path('admin', 'guestbook.php'); ?>">Návštěvní kniha</a></li>
        </ul>
    </nav>
</header>
