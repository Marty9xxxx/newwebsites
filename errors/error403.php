<?php
session_start();
// Načtení konfigurace 
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>403 - Zakázaný přístup</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h1>403 - Zakázaný přístup</h1>
            <p>Nemáte dostatečné oprávnění k zobrazení této stránky. Využijte tedy, prosíme, části stránek, ke kterým máte dostatečná práva.
                Máte-li pocit, že došlo k chybě, kontaktujte svého správce webu, případně kontaktujte nás přes <a href="<?php echo getWebPath('sites/contact.php'); ?>" target="_blank">email</a>
            </p>
        </section>
    </main>
    
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>