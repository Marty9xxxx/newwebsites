<?php
session_start();
// Načtení dat z JSONů
$users = json_decode(file_get_contents(path('data', 'users.json')), true);
$styles = json_decode(file_get_contents(path('data', 'styles.json')), true);

// Vložení hlavičky
include(path('includes', 'header.php'));

// Načtení novinek
$news = json_decode(file_get_contents(path('data', 'news.json')), true);

// Zpracování přidání novinky
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news'])) {
    $new_news = $_POST['news'];
    file_put_contents(path('data', 'news.json'), $new_news . PHP_EOL, FILE_APPEND);
    echo "Novinka přidána!";
}

// Uložíme do souboru
if (file_put_contents(path('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT))) {
    echo "Soubor news.json byl úspěšně upraven!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists(path('data', 'news.json'))) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents(path('data', 'news.json')));
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novinky</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo path('styles', 'styles.css'); ?>">
</head>
<body>

<main>
    <section class="content">
        <h2>Novinky</h2>
        <form method="post" action="news.php">
            <textarea name="news" placeholder="Napište novinku" required></textarea>
            <button type="submit">Přidat novinku</button>
        </form>

        <h3>Archiv novinek</h3>
        <ul>
            <?php
            $news = file(path('data', 'news.json'), FILE_IGNORE_NEW_LINES);
            if ($news !== false) {
                foreach ($news as $item) {
                    echo "<li>" . htmlspecialchars($item) . "</li>";
                }
            } else {
                echo "<li>Nepodařilo se načíst novinky.</li>";
            }
            ?>
        </ul>
    </section>
</main>

<?php include(path('includes', 'footer.php')); ?>
