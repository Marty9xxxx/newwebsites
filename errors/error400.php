<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat mezi požadavky
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';
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
