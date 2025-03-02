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