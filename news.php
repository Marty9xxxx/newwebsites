<link rel="stylesheet" href="<?= htmlspecialchars($aktualniStyl); ?>">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news'])) {
    $new_news = $_POST['news'];
    file_put_contents(__DIR__.'/data/data.txt', $new_news . PHP_EOL, FILE_APPEND);
    echo "Novinka přidána!";
}
?>

<?php include 'header.php'; ?>

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
            $news = file(__DIR__.'/data/data.txt', FILE_IGNORE_NEW_LINES);
            foreach ($news as $item) {
                echo "<li>$item</li>";
            }
            ?>
        </ul>
    </section>
</main>

<?php include 'footer.php'; ?>
