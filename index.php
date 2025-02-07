<link rel="stylesheet" href="<?= htmlspecialchars($aktualniStyl); ?>">
<link rel="stylesheet" href="style.css?v=<?= time(); ?>">
<a href="style1.css">Otevřít CSS</a>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'header.php'; ?>

</head>

<body>
<main>
    <section class="content">
        <h2>Vítejte na stránkách Svatoušek!</h2>
        <p>Na této stránce najdete všechny novinky a možnost se se mnou spojit.</p>
    </section>

    <section id="map">
        <h3>Mapa - Hlivická 118, Praha 8</h3>
        <div id="mapid" style="height: 300px;"></div>
    </section>
</main>
</body>

<?php include 'footer.php'; ?>
