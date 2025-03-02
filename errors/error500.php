<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';

// ====== LOGOVÁNÍ CHYBY ====== 
// Zaznamenání 500 chyby do logu serveru
error_log("500 Internal Server Error: " . $_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chybová stránka 500 - Interní serverová chyba">
    
    <!-- ====== TITULEK ====== -->
    <title>500 - Interní serverová chyba</title>
    
    <!-- ====== STYLY ====== -->
    <!-- Hlavní CSS styl webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <!-- CSS pro tisk -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- ====== HLAVIČKA ====== -->
    <!-- Načtení společné hlavičky webu -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <!-- Nadpis chybové stránky -->
            <h1>500 - Interní serverová chyba</h1>
            
            <!-- Informační zpráva pro uživatele -->
            <p>Omlouváme se, došlo k chybě na serveru. Zkuste to prosím později.</p>
            
            <!-- Kontaktní informace a odkazy -->
            <p>
                Pokud chyba přetrvává, kontaktujte svého správce webu, 
                případně nás kontaktujte přes 
                <a href="<?php echo getWebPath('sites/contact.php'); ?>" 
                   target="_blank">email</a>
            </p>

            <!-- Tlačítka pro navigaci -->
            <div class="error-actions">
                <a href="<?php echo getWebPath('index.php'); ?>" class="button">
                    Zpět na hlavní stránku
                </a>
            </div>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <!-- Načtení společné patičky webu -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>