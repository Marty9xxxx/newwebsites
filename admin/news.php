<?php
session_start();

// Kontrola přihlášení a role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Zpracování odhlášení
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// Načtení potřebných dat
$users = json_decode(file_get_contents('../data/users.json'), true);
$news = json_decode(file_get_contents('../data/news.json'), true);

// Zpracování přidání novinky
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news'])) {
    $new_news = $_POST['news'];
    file_put_contents('../data/news.json', $new_news . PHP_EOL, FILE_APPEND);
    echo "Novinka přidána!";
}

// Uložíme do souboru
if (file_put_contents('../data/news.json', json_encode($news, JSON_PRETTY_PRINT))) {
    echo "Soubor news.json byl úspěšně upraven!<br>";
} else {
    echo "Nastala chyba při vytváření souboru!<br>";
}

// Pro kontrolu přečteme a vypíšeme obsah
if (file_exists('../data/news.json')) {
    echo "<pre>";
    echo htmlspecialchars(file_get_contents('../data/news.json'));
    echo "</pre>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Novinky</title>
    <?php include 'header.php'; ?>
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
            $news = file('../data/news.json', FILE_IGNORE_NEW_LINES);
            foreach ($news as $item) {
                echo "<li>$item</li>";
            }
            ?>
        </ul>
    </section>
</main>

<?php include 'footer.php'; ?>
