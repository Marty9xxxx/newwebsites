<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== NAČTENÍ DAT ======
// Načtení novinek z JSON souboru a převod na PHP pole
// Použití getFilePath pro bezpečnou práci s cestami
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);

// ====== ZPRACOVÁNÍ FORMULÁŘŮ ======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrola přihlášení uživatele
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        header('Location: ' . getWebPath('admin/login.php'));
        exit;
    }
    
    // ====== PŘIDÁNÍ NOVÉ NOVINKY ======
    if (isset($_POST['news'])) {
        // Vytvoření nové novinky s metadaty
        $new_news = [
            'author' => $_SESSION['username'] ?? 'Anonym', // Autor nebo výchozí hodnota
            'text' => $_POST['news'],                      // Text novinky
            'datetime' => date('Y-m-d H:i:s')             // Aktuální čas
        ];
        // Vložení na začátek pole (nejnovější první)
        array_unshift($news, $new_news);
        
        // Uložení do JSON s formátováním pro čitelnost
        file_put_contents(
            getFilePath('data', 'news.json'), 
            json_encode($news, JSON_PRETTY_PRINT)
        );
        
        // Přesměrování s potvrzením
        header('Location: admin.php?section=news&success=1');
        exit;
    }

    // ====== ÚPRAVA EXISTUJÍCÍ NOVINKY ======
    // Pouze pro administrátory
    if (isset($_POST['edit_news']) && isset($_POST['news_id']) && $_SESSION['role'] === 'admin') {
        $news_id = $_POST['news_id'];
        // Aktualizace textu a času úpravy
        $news[$news_id]['text'] = $_POST['edit_news'];
        $news[$news_id]['datetime'] = date('Y-m-d H:i:s');
        
        // Uložení změn
        file_put_contents(
            getFilePath('data', 'news.json'), 
            json_encode($news, JSON_PRETTY_PRINT)
        );
        
        // Přesměrování s potvrzením
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
            <!-- ====== SEKCE PRO PŘIHLÁŠENÉ UŽIVATELE ====== -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <h2>Správa novinek</h2>
                
                <!-- Potvrzení úspěšné operace -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="message success">Novinka byla úspěšně uložena.</div>
                <?php endif; ?>
                
                <!-- Formulář pro přidání novinky -->
                <form method="post" action="admin.php?section=news">
                    <textarea name="news" placeholder="Napište novinku" required></textarea>
                    <button type="submit">Přidat novinku</button>
                </form>
            <?php else: ?>
                <h2>Novinky</h2>
            <?php endif; ?>

            <!-- ====== VÝPIS NOVINEK ====== -->
            <div class="news-list">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $index => $item): ?>
                        <div class="news-item">
                            <!-- Záhlaví novinky s metadaty -->
                            <div class="news-header">
                                <span class="news-date">
                                    <?php echo htmlspecialchars($item['datetime']); ?>
                                </span>
                                <span class="news-author">
                                    <?php echo htmlspecialchars($item['author']); ?>
                                </span>
                            </div>
                            
                            <!-- Text novinky -->
                            <div class="news-text">
                                <?php echo htmlspecialchars($item['text']); ?>
                            </div>
                            
                            <!-- Akce pro administrátory -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="news-actions">
                                    <a href="admin.php?section=news&edit=<?php echo $index; ?>">
                                        Upravit
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nepodařilo se načíst novinky.</p>
                <?php endif; ?>
            </div>

            <!-- ====== FORMULÁŘ PRO ÚPRAVU NOVINKY ====== -->
            <?php
            // Zobrazení formuláře pro úpravu - pouze pro adminy
            if (isset($_GET['edit']) && $_SESSION['role'] === 'admin') {
                $edit_id = $_GET['edit'];
                $edit_news = $news[$edit_id]['text'];
                ?>
                <h3>Upravit novinku</h3>
                <form method="post" action="admin.php?section=news">
                    <textarea name="edit_news" required>
                        <?php echo htmlspecialchars($edit_news); ?>
                    </textarea>
                    <input type="hidden" name="news_id" value="<?php echo $edit_id; ?>">
                    <button type="submit">Upravit novinku</button>
                </form>
            <?php } ?>
        </section>
    </main>

    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
