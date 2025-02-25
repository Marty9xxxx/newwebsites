<?php
session_start();
// Načtení konfigurace
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>401 - Neautorizovaný přístup</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h1>401 - Neautorizovaný přístup</h1>
            <p>Pro přístup k této stránce se musíte přihlásit.</p>
        </section>
    </main>
    
    <?php include(getFilePath('includes', 'footer.php')); ?>
<link rel="stylesheet" href="style1.css">
</body>
</html>