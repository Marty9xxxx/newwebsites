<?php
session_start();
// Načtení konfigurace
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>404 - Stránka nenalezena</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h1>404 - Stránka nenalezena</h1>
            <p>...Nebyla nalezena, zkuste zadat přesný název, případně využijte hledání na webu. Máte-li pocit, že se jedná o chybu, kontaktujte svého správce webu, případně kontaktujte nás přes <a href="<?php echo getWebPath('sites/contact.php'); ?>" target="_blank">email</a></p>
        </section>
    </main>
    
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>