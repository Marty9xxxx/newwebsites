<?php
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Svatoušek</title>
    <link rel="stylesheet" href="<?php 
        $styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
        echo getWebPath('styles/' . ($styles['currentStyle'] ?? 'default') . '.css');
    ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
</head>
<header>
    <h1><a href="<?php echo getWebPath('index.php'); ?>">Svatoušek</a></h1>
    <nav>
        <ul>
            <li><a href="<?php echo getWebPath('includes/login.php'); ?>">Přihlášení</a></li>
            <li><a href="<?php echo getWebPath('includes/logout.php'); ?>">Odhlásit se</a></li>
            <li><a href="<?php echo getWebPath('includes/register.php'); ?>">Registrace</a></li>
            <li><a href="<?php echo getWebPath('index.php'); ?>">Úvod</a></li>
            <li><a href="<?php echo getWebPath('sites/contact.php'); ?>">Napište mi</a></li>
            <li><a href="<?php echo getWebPath('admin/news.php'); ?>">Novinky</a></li>
            <li><a href="<?php echo getWebPath('admin/admin.php'); ?>">Administrace</a></li>
            <li><a href="<?php echo getWebPath('errors/error404.php'); ?>">Chyba!</a></li>
            <li><a href="<?php echo getWebPath('sites/portfolio.php'); ?>">Portfolio</a></li>
            <li><a href="<?php echo getWebPath('admin/guestbook.php'); ?>">Návštěvní kniha</a></li>
        </ul>
    </nav>
</header>