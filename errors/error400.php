<?php
session_start();
// Načtení konfigurace
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>400 - Špatný požadavek</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="(getWebPath('styles/print.css'))" media="print">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h1>400 - Špatný požadavek</h1>
            <p>Omlouváme se, ale stránka, kterou hledáte, má chybný požadavek. Zkuste vyvolání opakovat, zadat požadavek jiným způsobem, případně kontaktujte svého správce webu či kontaktujte nás přes <a href="<?php echo getWebPath('sites/contact.php'); ?>" target="_blank">email</a></p>
        </section>
    </main>
    
<link rel="stylesheet" href="(getWebPath('styles/style1.css'))">

<?php include (getFilePath('includes','footer.php')); ?>
</body>
</html>
