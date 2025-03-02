<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chybová stránka 403 - Zakázaný přístup">
    
    <!-- ====== TITULEK ====== -->
    <title>403 - Zakázaný přístup</title>
    
    <!-- ====== STYLY ====== -->
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
        <section class="content">
            <!-- Nadpis chybové stránky -->
            <h1>403 - Zakázaný přístup</h1>
            
            <!-- Vysvětlující text s odkazem na kontakt -->
            <p>
                Nemáte dostatečné oprávnění k zobrazení této stránky. 
                Využijte tedy, prosíme, části stránek, ke kterým máte dostatečná práva.
                Máte-li pocit, že došlo k chybě, kontaktujte svého správce webu, 
                případně kontaktujte nás přes 
                <a href="<?php echo getWebPath('sites/contact.php'); ?>" 
                   target="_blank">email</a>
            </p>

            <!-- Tlačítka pro navigaci -->
            <div class="error-actions">
                <a href="<?php echo getWebPath('index.php'); ?>" class="button">
                    Zpět na hlavní stránku
                </a>
                <a href="<?php echo getWebPath('includes/login.php'); ?>" class="button secondary">
                    Přihlásit se
                </a>
            </div>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <!-- Načtení společné patičky webu -->
    <?php include(getFilePath('includes', 'footer.php')); ?>

    <!-- ====== LOGOVÁNÍ CHYBY ====== -->
    <?php
    // Zalogování pokusu o neautorizovaný přístup
    error_log(sprintf(
        "403 Forbidden: %s - IP: %s - User: %s",
        $_SERVER['REQUEST_URI'],
        $_SERVER['REMOTE_ADDR'],
        $_SESSION['username'] ?? 'nepřihlášený'
    ));
    ?>
</body>
</html>