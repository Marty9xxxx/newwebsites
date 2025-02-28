<?php
// Načtení konfigurace - potřebujeme pro funkci getFilePath
require_once dirname(__DIR__) . '/config.php';

// Načtení novinek z JSON souboru
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);

// Pro přidávání a úpravu novinek musí být uživatel přihlášený
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        header('Location: ' . getWebPath('admin/login.php'));
        exit;
    }
    
    // Zpracování přidání nové novinky
    if (isset($_POST['news'])) {
        // Vytvoření nové novinky s autorem, textem a časem
        // Použijeme ?? operátor pro případ, že by username nebylo nastaveno
        $new_news = [
            'author' => $_SESSION['username'] ?? 'Anonym',
            'text' => $_POST['news'],
            'datetime' => date('Y-m-d H:i:s')
        ];
        // Přidání novinky na začátek pole
        array_unshift($news, $new_news); // Přidá novinku na začátek pole
        // Uložení do JSON souboru
        file_put_contents(getFilePath('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
        // Přesměrování zpět na seznam novinek
        header('Location: admin.php?section=news&success=1');
        exit;
    }

    // Zpracování úpravy existující novinky (pouze pro adminy)
    if (isset($_POST['edit_news']) && isset($_POST['news_id']) && $_SESSION['role'] === 'admin') {
        $news_id = $_POST['news_id'];
        // Aktualizace textu a času úpravy
        $news[$news_id]['text'] = $_POST['edit_news'];
        $news[$news_id]['datetime'] = date('Y-m-d H:i:s');
        // Uložení změn do JSON souboru
        file_put_contents(getFilePath('data', 'news.json'), json_encode($news, JSON_PRETTY_PRINT));
        // Přesměrování zpět na seznam novinek
        header('Location: admin.php?section=news&success=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Novinky</title>
</head>
<body>
    <main>
        <section class="content">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <h2>Správa novinek</h2>
                <?php if (isset($_GET['success'])): ?>
                    <div class="message success">Novinka byla úspěšně uložena.</div>
                <?php endif; ?>
                <!-- Formulář pro přidání nové novinky - pouze pro přihlášené -->
                <form method="post" action="admin.php?section=news">
                    <textarea name="news" placeholder="Napište novinku" required></textarea>
                    <button type="submit">Přidat novinku</button>
                </form>
            <?php else: ?>
                <h2>Novinky</h2>
            <?php endif; ?>

            <!-- Výpis všech novinek - viditelný pro všechny -->
            <div class="news-list">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $index => $item): ?>
                        <div class="news-item">
                            <div class="news-header">
                                <span class="news-date"><?php echo htmlspecialchars($item['datetime']); ?></span>
                                <span class="news-author"><?php echo htmlspecialchars($item['author']); ?></span>
                            </div>
                            <div class="news-text"><?php echo htmlspecialchars($item['text']); ?></div>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="news-actions">
                                    <a href="admin.php?section=news&edit=<?php echo $index; ?>">Upravit</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nepodařilo se načíst novinky.</p>
                <?php endif; ?>
            </div>

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
