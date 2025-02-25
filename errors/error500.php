<?php
session_start();    // Start the session
// Load the configuration   
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>500 - Interní serverová chyba</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h1>500 - Interní serverová chyba</h1>
            <p>Omlouváme se, došlo k chybě na serveru. Zkuste to prosím později.</p>
            <p>Pokud chyba přetrvává, kontaktujte svého správce webu, případně nás kontaktujte přes <a href="<?php echo getWebPath('sites/contact.php'); ?>" target="_blank">email</a></p>
        </section>
    </main>
    
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>