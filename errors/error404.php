<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== LOGOVÁNÍ CHYBY ====== 
// Zaznamenání 404 chyby do logu
require_once dirname(__DIR__) . '/includes/Logger.php';

$logger = new Logger('errors');
$logger->write(
    sprintf(
        "404 Not Found: %s | IP: %s | Referrer: %s",
        $_SERVER['REQUEST_URI'],
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_REFERER'] ?? 'přímý přístup'
    ),
    'ERROR'
);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chybová stránka 404 - Stránka nenalezena">
    
    <!-- ====== TITULEK ====== -->
    <title>404 - Stránka nenalezena</title>
    
    <!-- ====== STYLY ====== -->
    <!-- Hlavní CSS styl webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <!-- CSS pro tisk -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- ====== HLAVIČKA ====== -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <!-- Nadpis chybové stránky -->
            <h1>404 - Stránka nenalezena</h1>
            
            <!-- Vysvětlující text s odkazy -->
            <p>
                Omlouváme se, ale požadovaná stránka nebyla nalezena. 
                Zkuste prosím následující:
            </p>
            
            <!-- Seznam možných řešení -->
            <ul>
                <li>Zkontrolovat správnost zadané adresy</li>
                <li>Využít navigaci v menu webu</li>
                <li>Použít vyhledávání na webu</li>
            </ul>
            
            <!-- Kontaktní informace -->
            <p>
                Máte-li pocit, že se jedná o chybu, 
                kontaktujte nás prosím přes 
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
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>