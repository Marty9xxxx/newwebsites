<?php
require_once '../config.php'; // Přidáno pro načtení funkce path

session_start();
// Načtení dat z JSONů
$news = json_decode(file_get_contents(path('data', 'news.json')), true);

// Zpracování přidání novinky
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news'])) {
    $new_news = [
        'author' => $_SESSION['username'],
        'text' => $_POST['news'],
        'datetime' => date('Y-m-d H:i:s')
    ];
    $news[] = $new_news;
    file_put_contents(path('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
    header('Location: admin.php?section=news');
    exit;
}

// Zpracování úpravy novinky
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_news']) && isset($_POST['news_id']) && $_SESSION['role'] === 'admin') {
    $news_id = $_POST['news_id'];
    $news[$news_id]['text'] = $_POST['edit_news'];
    $news[$news_id]['datetime'] = date('Y-m-d H:i:s');
    file_put_contents(path('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
    header('Location: admin.php?section=news');
    exit;
}
?>

<main>
    <section class="content">
        <h2>Správa novinek</h2>
        <form method="post" action="admin.php?section=news">
            <textarea name="news" placeholder="Napište novinku" required></textarea>
            <button type="submit">Přidat novinku</button>
        </form>

        <h3>Archiv novinek</h3>
        <ul>
            <?php
            $news = json_decode(file_get_contents(path('data', 'news.json')), true);
            if ($news !== false) {
                foreach ($news as $index => $item) {
                    echo "<li>" . htmlspecialchars($item['datetime'] . ' - ' . $item['author'] . ': ' . $item['text']);
                    if ($_SESSION['role'] === 'admin') {
                        echo ' <a href="admin.php?section=news&edit=' . $index . '">Upravit</a>';
                    }
                    echo "</li>";
                }
            } else {
                echo "<li>Nepodařilo se načíst novinky.</li>";
            }
            ?>
        </ul>

        <?php
        // Formulář pro úpravu novinky
        if (isset($_GET['edit']) && $_SESSION['role'] === 'admin') {
            $edit_id = $_GET['edit'];
            $edit_news = $news[$edit_id]['text'];
            echo '<h3>Upravit novinku</h3>';
            echo '<form method="post" action="admin.php?section=news">';
            echo '<textarea name="edit_news" required>' . htmlspecialchars($edit_news) . '</textarea>';
            echo '<input type="hidden" name="news_id" value="' . $edit_id . '">';
            echo '<button type="submit">Upravit novinku</button>';
            echo '</form>';
        }
        ?>
    </section>
</main>

<?php include(path('includes', 'footer.php')); ?>
</body>
</html>
