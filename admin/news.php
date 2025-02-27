<?php
// Načtení konfigurace - potřebujeme pro funkci getFilePath
require_once dirname(__DIR__) . '/config.php';

// Kontrola přihlášení - bez této kontroly by nefungovala práce se session
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ' . getWebPath('admin/login.php'));
    exit;
}

// Načtení novinek z JSON souboru
// Použijeme getFilePath pro získání správné cesty k souboru
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);

// Zpracování přidání nové novinky
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['news'])) {
    // Vytvoření nové novinky s autorem, textem a časem
    // Použijeme ?? operátor pro případ, že by username nebylo nastaveno
    $new_news = [
        'author' => $_SESSION['username'] ?? 'Anonym',
        'text' => $_POST['news'],
        'datetime' => date('Y-m-d H:i:s')
    ];
    // Přidání novinky na začátek pole
    $news[] = $new_news;
    // Uložení do JSON souboru
    file_put_contents(getFilePath('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
    // Přesměrování zpět na seznam novinek
    header('Location: admin.php?section=news');
    exit;
}

// Zpracování úpravy existující novinky (pouze pro adminy)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_news']) && isset($_POST['news_id']) && $_SESSION['role'] === 'admin') {
    $news_id = $_POST['news_id'];
    // Aktualizace textu a času úpravy
    $news[$news_id]['text'] = $_POST['edit_news'];
    $news[$news_id]['datetime'] = date('Y-m-d H:i:s');
    // Uložení změn do JSON souboru
    file_put_contents(getFilePath('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
    // Přesměrování zpět na seznam novinek
    header('Location: admin.php?section=news');
    exit;
}
?>

<main>
    <section class="content">
        <h2>Správa novinek</h2>
        <!-- Formulář pro přidání nové novinky -->
        <form method="post" action="admin.php?section=news">
            <textarea name="news" placeholder="Napište novinku" required></textarea>
            <button type="submit">Přidat novinku</button>
        </form>

        <h3>Archiv novinek</h3>
        <!-- Výpis všech novinek -->
        <ul>
            <?php
            // Znovu načteme novinky pro případ, že byly upraveny
            $news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);
            if ($news !== false) {
                foreach ($news as $index => $item) {
                    // Výpis jednotlivé novinky s časem, autorem a textem
                    echo "<li>" . htmlspecialchars($item['datetime'] . ' - ' . $item['author'] . ': ' . $item['text']);
                    // Tlačítko pro úpravu se zobrazí pouze adminům
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
        // Formulář pro úpravu novinky - zobrazí se pouze adminům po kliknutí na "Upravit"
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

<?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
