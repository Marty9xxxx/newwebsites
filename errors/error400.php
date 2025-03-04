<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat mezi požadavky
session_start();

// ====== NAČTENÍ KONFIGURACE A LOGGERU ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/Logger.php';

// ====== LOGOVÁNÍ CHYBY ======
$logger = new Logger('400_errors');
$logger->log('Špatný požadavek', [
    'requested_page' => $_SERVER['REQUEST_URI'] ?? 'neznámá stránka',
    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'neznámá metoda',
    'query_string' => $_SERVER['QUERY_STRING'] ?? 'žádné parametry',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'neznámá IP',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'neznámý prohlížeč',
    'referrer' => $_SERVER['HTTP_REFERER'] ?? 'přímý přístup',
    'post_data' => !empty($_POST) ? 'obsahuje POST data' : 'bez POST dat',
    'timestamp' => date('Y-m-d H:i:s')
]);
?>

<!DOCTYPE html>
<html>
<head>
    <!-- ====== META INFORMACE A NÁZEV ====== -->
    <title>400 - Špatný požadavek</title>
    
    <!-- ====== NAČTENÍ STYLŮ ====== -->
    <!-- Hlavní CSS styl webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    
    <!-- CSS pro tisk - použije se pouze při tisku stránky -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- ====== VLOŽENÍ HLAVIČKY ====== -->
    <!-- Načtení společné hlavičky webu s meta tagy a dalšími prvky -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <!-- Sekce s chybovou zprávou -->
        <section class="content">
            <!-- Nadpis chybové stránky -->
            <h1>400 - Špatný požadavek</h1>
            
            <!-- Vysvětlující text s odkazem na kontakt -->
            <p>
                Omlouváme se, ale stránka, kterou hledáte, má chybný požadavek. 
                Zkuste vyvolání opakovat, zadat požadavek jiným způsobem, 
                případně kontaktujte svého správce webu či kontaktujte nás přes 
                <a href="<?php echo getWebPath('sites/contact.php'); ?>" 
                   target="_blank">email</a>
            </p>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <!-- Načtení společné patičky webu -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
