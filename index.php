<?php session_start();
// Načtení konfigurace
require_once './config.php';

// Načtení dat z JSONů
$users = json_decode(file_get_contents(path('data', 'users.json')), true);
$styles = json_decode(file_get_contents(path('data', 'styles.json')), true);

// Vložení hlavičky
include(path('includes', 'header.php')); ?>

<section class="search">
<form action="search.php" method="get">
    <input type="text" name="q" placeholder="Napiš text" required><button type="submit">Hledat</button>
</form>
</section>

<main>
    <section class="content">
        <h2>Vítejte na stránkách Svatoušek!</h2>
        <p>Na této stránce najdete všechny informace i možnost se se mnou spojit.</p>
    </section>

<?php include(path('includes', 'header.php')); ?>
