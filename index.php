<?php
session_start();
// Načtení konfigurace  
require_once dirname(__DIR__) . 'config.php';

// Načtení dat z JSONů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// Vložení hlavičky
include(getFilePath('includes', 'header.php'));
?>

<section class="search">
    <form action="<?php echo getWebPath('includes/search.php'); ?>" method="get">
        <input type="text" name="keywords" placeholder="Napište text" required>
        <button type="submit">Hledat</button>
    </form>
</section>

<main>
    <section class="content">
        <h2>Vítejte na stránkách Svatoušek!</h2>
        <p>Na této stránce najdete všechny informace i možnost se se mnou spojit.</p>
    </section>
</main>

<?php include(getFilePath('includes', 'footer.php')); ?>
