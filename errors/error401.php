<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro zachování přihlášení a dalších dat
session_start();

// ====== NAČTENÍ KONFIGURACE A LOGGERU ======
// Načtení konfiguračního souboru s pomocnými funkcemi
// dirname(__DIR__) získá nadřazenou složku (root webu)
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/Logger.php';

// ====== LOGOVÁNÍ CHYBY ======
$logger = new Logger('401_errors');
$logger->log('Neautorizovaný přístup', [
    'requested_page' => $_SERVER['REQUEST_URI'] ?? 'neznámá stránka',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'neznámá IP',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'neznámý prohlížeč',
    'referrer' => $_SERVER['HTTP_REFERER'] ?? 'přímý přístup',
    'user' => $_SESSION['username'] ?? 'nepřihlášený uživatel',
    'timestamp' => date('Y-m-d H:i:s')
]);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chybová stránka 401 - Neautorizovaný přístup">
    
    <!-- ====== TITULEK ====== -->
    <title>401 - Neautorizovaný přístup</title>
    
    <!-- ====== STYLY ====== -->
    <!-- Načtení hlavního stylu webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    
    <!-- ====== HLAVIČKA ====== -->
    <!-- Vložení společné hlavičky webu -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <!-- ====== HLAVNÍ OBSAH ====== -->
    <main>
        <section class="content">
            <!-- Nadpis chybové stránky -->
            <h1>401 - Neautorizovaný přístup</h1>
            
            <!-- Informační zpráva pro uživatele -->
            <p>Pro přístup k této stránce se musíte přihlásit.</p>
            
            <!-- Tlačítko pro přesměrování na přihlášení -->
            <div class="error-actions">
                <a href="<?php echo getWebPath('includes/login.php'); ?>" class="button">
                    Přihlásit se
                </a>
                <a href="<?php echo getWebPath('index.php'); ?>" class="button secondary">
                    Zpět na hlavní stránku
                </a>
            </div>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <!-- Vložení společné patičky webu -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>