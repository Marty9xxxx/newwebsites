<?php

// Load the configuration   
require_once dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Portfolio</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
  <div class="portfolio-item">
    <h3>Osobní stránky</h3>
    <p>Osobní stránky autora projektu a webblog.</p>
    
    <iframe src="https://www.svatousek.cz" width="300" height="200" frameborder="0" allowfullscreen></iframe>
    
    <a href="https://www.svatousek.cz" target="_blank">Zobrazit plnou verzi</a>
</div>
<div class="portfolio-item">
    <h3>Online Sunlight průvodce</h3>
    <p>Zpracovávaná nápověda k systému Sunlight CMS, vše dle času a chuti.</p>
    
    <iframe src="https://it.svatousek.cz" width="300" height="200" frameborder="0" allowfullscreen></iframe>

    <a href	="https://it.svatousek.cz" target="_blank">Zobrazit plnou verzi</a>
</div>
<div class="portfolio-item">
    <h3>Endora/Webglobe hosting</h3>
    <p>Aneb i na Endoře Sunlight CMS jede bez větších potíží.</p>
    
    <iframe src="https://martysweb.4fan.cz" width="300" height="200" frameborder="0" allowfullscreen></iframe>
    <a href="https://martysweb.4fan.cz" target="_blank">Zobrazit plnou verzi</a>
</div>
<?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>