<?php
// Na začátek souboru přidáme:
require_once dirname(__DIR__) . '/includes/TinyMCEEditor.php';

// Přidáme načtení helper funkce
require_once dirname(__DIR__) . '/includes/editor_helper.php';

// Kontrola výstupu před přesměrováním
ob_start();

// Načtení konfigurace
require_once dirname(__DIR__) . '/config.php';

// Kontrola přihlášení
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ' . getWebPath('admin/login.php'));
    ob_end_flush();
    exit;
}

// ====== NAČTENÍ DAT ======
// Inicializace struktury dat
$articlesData = json_decode(file_get_contents(getFilePath('data', 'articles.json')), true) ?? ['articles' => []];
$articles = &$articlesData['articles']; // Reference na pole článků

// ====== ZPRACOVÁNÍ FORMULÁŘŮ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            // Kontrola povinných polí
            if (empty($_POST['title']) || empty($_POST['perex']) || empty($_POST['content'])) {
                $error = "Vyplňte prosím všechna povinná pole!";
                break;
            }

            $new_article = [
                'id' => time(),
                'title' => trim($_POST['title']),
                'perex' => trim($_POST['perex']),
                'content' => trim($_POST['content']),
                'author' => $_SESSION['username'] ?? 'admin',
                'datetime' => date('Y-m-d H:i:s'),
                'published' => isset($_POST['published']) ? true : false
            ];
            
            // Přidání nového článku na začátek pole
            array_unshift($articles, $new_article);
            
            // Uložení do souboru
            if (file_put_contents(
                getFilePath('data', 'articles.json'),
                json_encode(['articles' => $articles], JSON_PRETTY_PRINT)
            )) {
                header('Location: admin.php?section=articles&success=1');
                ob_end_flush();
                exit;
            } else {
                $error = "Chyba při ukládání článku!";
            }
            break;

        case 'edit':
            $article_id = $_POST['article_id'];
            foreach ($articles as &$article) {
                if ($article['id'] == $article_id) {
                    $article['title'] = $_POST['title'];
                    $article['perex'] = $_POST['perex'];
                    $article['content'] = $_POST['content'];
                    $article['published'] = isset($_POST['published']);
                    $article['last_edited'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            
            // Přidej před uložení pro kontrolu práv
            var_dump(is_writable(getFilePath('data', 'articles.json')));
            exit;

            // Uložení změn
            if (file_put_contents(
                getFilePath('data', 'articles.json'),
                json_encode($articlesData, JSON_PRETTY_PRINT)
            )) {
                header('Location: admin.php?section=articles&success=1');
                ob_end_flush();
                exit;
            }
            break;
    }
}
?>

<!-- ====== HTML ČÁST ====== -->
<section class="content">
    <h2>Správa článků</h2>
    
    <!-- Zobrazení zprávy o úspěšném uložení -->
    <?php if (isset($_GET['success'])): ?>
        <div class="message success">Článek byl úspěšně uložen.</div>
    <?php endif; ?>

    <!-- Formulář pro přidání/editaci článku -->
    <form method="post" action="admin.php?section=articles">
        <!-- Skryté pole určující akci (přidání/úprava) -->
        <input type="hidden" name="action" value="<?php echo isset($_GET['edit']) ? 'edit' : 'add'; ?>">
        <?php
        // Načtení článku pro úpravu, pokud je zadáno ID
        $article = null;
        if (isset($_GET['edit'])) {
            foreach ($articles as $a) {
                if ($a['id'] == $_GET['edit']) {
                    $article = $a;
                    break;
                }
            }
        }
        ?>
        
        <!-- ID článku pro úpravu -->
        <?php if ($article): ?>
            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
        <?php endif; ?>

        <!-- Formulářové pole -->
        <div>
            <label for="title">Název článku:</label>
            <input type="text" id="title" name="title" 
                   value="<?php echo $article ? htmlspecialchars($article['title']) : ''; ?>" required>
        </div>

        <div>
            <label for="perex">Perex:</label>
            <textarea id="perex" name="perex" required><?php 
                echo $article ? htmlspecialchars($article['perex']) : ''; 
            ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Obsah článku:</label>
            <?php
            // Použijeme helper funkci
            $content = isset($article) ? $article['content'] : '';
            $editor = getUserPreferredEditor('content', $content);
            $editor->render();
            ?>
        </div>

        <!-- Checkbox pro publikování -->
        <div>
            <label>
                <input type="checkbox" name="published" 
                       <?php echo ($article && $article['published']) ? 'checked' : ''; ?>>
                Publikovat článek
            </label>
        </div>

        <!-- Tlačítko pro odeslání -->
        <button type="submit">
            <?php echo $article ? 'Upravit článek' : 'Přidat článek'; ?>
        </button>
    </form>

    <!-- Seznam existujících článků -->
    <h3>Seznam článků</h3>
    <div class="articles-list">
        <?php foreach ($articles as $article): ?>
            <!-- Článek s barevným odlišením podle stavu -->
            <div class="article-item <?php echo $article['published'] ? 'published' : 'draft'; ?>">
                <h4><?php echo htmlspecialchars($article['title']); ?></h4>
                <div class="article-meta">
                    Autor: <?php echo htmlspecialchars($article['author']); ?> |
                    Datum: <?php echo htmlspecialchars($article['datetime']); ?> |
                    Stav: <?php echo $article['published'] ? 'Publikováno' : 'Koncept'; ?>
                </div>
                <div class="article-actions">
                    <a href="admin.php?section=articles&edit=<?php echo $article['id']; ?>">Upravit</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====== CSS STYLY ====== -->
<style>
/* Styly pro formulář */
.article-form {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
}

.article-form div {
    margin-bottom: 15px;
}

.article-form label {
    display: block;
    margin-bottom: 5px;
}

/* Styly pro vstupní pole */
.article-form input[type="text"],
.article-form textarea {
    width: 100%;
    padding: 8px;
}

/* Editor obsahu */
.article-form textarea.editor {
    min-height: 300px;
}

/* Seznam článků */
.articles-list {
    margin-top: 20px;
}

/* Jednotlivé články */
.article-item {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
}

/* Barevné odlišení stavů */
.article-item.draft {
    background: #fff5f5;  /* Světle červená pro koncepty */
}

.article-item.published {
    background: #f5fff5;  /* Světle zelená pro publikované */
}

/* Meta informace článku */
.article-meta {
    font-size: 0.9em;
    color: #666;
    margin: 5px 0;
}

/* Akční tlačítka */
.article-actions {
    margin-top: 10px;
}
</style>

<!-- Přidat před uzavírací tag </body> -->
<script>
tinymce.init({
    selector: '.editor', // Třída pro textareas, které chceme změnit na editor
    plugins: 'lists link image table code help wordcount',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link image | code',
    height: 400,
    language: 'cs',
    skin: 'oxide-dark',
    content_css: 'dark',
    menubar: true,
    branding: false,
    // České rozhraní
    language_url: 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/langs/cs.js',
    // Automatické ukládání rozpracovaného obsahu
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    // Nastavení pro nahrávání obrázků
    images_upload_url: 'upload.php',
    automatic_uploads: true
});
</script>