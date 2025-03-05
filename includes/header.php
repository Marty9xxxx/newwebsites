<?php
// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== ZÁKLADNÍ META INFORMACE ====== -->
    <title>Svatoušek</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ====== NAČTENÍ STYLŮ ====== -->
    <!-- Dynamické načtení aktuálního stylu ze souboru styles.json -->
    <link rel="stylesheet" href="<?php 
        // Načtení konfigurace stylů z JSON
        $styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
        // Použití aktuálního stylu nebo výchozího, pokud není nastaven
        echo getWebPath('styles/' . ($styles['currentStyle'] ?? 'default') . '.css');
    ?>">
    
    <!-- Font Awesome pro sociální ikony -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Speciální styl pro tisk -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- TinyMCE WYSIWYG Editor -->
    <script src="https://cdn.tiny.cloud/1/bmh9epsdxw8n5foagoedb6lnfmpsvxm4xre0iavd4c3u0j35/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Simple Editor JavaScript -->
    <script src="<?php echo getWebPath('js/simple_editor.js'); ?>"></script>
</head>

<!-- ====== HLAVIČKA WEBU ====== -->
<header>
    <!-- Logo a název webu s odkazem na úvodní stránku -->
    <h1><a href="<?php echo getWebPath('index.php'); ?>">Svatoušek</a></h1>
    
    <!-- Hlavní navigace -->
    <nav>
        <ul>
            <!-- ====== UŽIVATELSKÁ SEKCE ====== -->
            <li><a href="<?php echo getWebPath('includes/login.php'); ?>">Přihlášení</a></li>
            <li><a href="<?php echo getWebPath('includes/logout.php'); ?>">Odhlásit se</a></li>
            <li><a href="<?php echo getWebPath('includes/register.php'); ?>">Registrace</a></li>
            
            <!-- ====== HLAVNÍ NAVIGACE ====== -->
            <li><a href="<?php echo getWebPath('index.php'); ?>">Úvod</a></li>
            <li><a href="<?php echo getWebPath('sites/articles.php'); ?>">Články</a></li>
            <li><a href="<?php echo getWebPath('sites/contact.php'); ?>">Napište mi</a></li>
            <li><a href="<?php echo getWebPath('sites/news.php'); ?>">Novinky</a></li>
            
            <!-- ====== ADMINISTRACE A SPECIÁLNÍ STRÁNKY ====== -->
            <li><a href="<?php echo getWebPath('admin/admin.php'); ?>">Administrace</a></li>
            <li><a href="<?php echo getWebPath('errors/error404.php'); ?>">Chyba!</a></li>
            <li><a href="<?php echo getWebPath('sites/portfolio.php'); ?>">Portfolio</a></li>
            <li><a href="<?php echo getWebPath('sites/guestbook.php'); ?>">Návštěvní kniha</a></li>
        </ul>
    </nav>
</header>