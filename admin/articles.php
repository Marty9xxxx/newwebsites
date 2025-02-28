<?php
require_once dirname(__DIR__) . '/config.php';

// Načtení článků
$articlesData = json_decode(file_get_contents(getFilePath('data', 'articles.json')), true);
$articles = $articlesData['articles'] ?? [];

// Zpracování formulářů
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $new_article = [
                    'id' => time(), // Použijeme timestamp jako ID
                    'title' => $_POST['title'],
                    'perex' => $_POST['perex'],
                    'content' => $_POST['content'],
                    'author' => $_SESSION['username'],
                    'datetime' => date('Y-m-d H:i:s'),
                    'published' => isset($_POST['published'])
                ];
                array_unshift($articlesData['articles'], $new_article);
                break;

            case 'edit':
                $article_id = $_POST['article_id'];
                foreach ($articlesData['articles'] as &$article) {
                    if ($article['id'] == $article_id) {
                        $article['title'] = $_POST['title'];
                        $article['perex'] = $_POST['perex'];
                        $article['content'] = $_POST['content'];
                        $article['published'] = isset($_POST['published']);
                        $article['last_edited'] = date('Y-m-d H:i:s');
                        break;
                    }
                }
                break;
        }
        
        file_put_contents(getFilePath('data', 'articles.json'), json_encode($articlesData, JSON_PRETTY_PRINT));
        header('Location: admin.php?section=articles&success=1');
        exit;
    }
}
?>

<section class="content">
    <h2>Správa článků</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="message success">Článek byl úspěšně uložen.</div>
    <?php endif; ?>

    <!-- Formulář pro přidání/editaci článku -->
    <form method="post" class="article-form">
        <input type="hidden" name="action" value="<?php echo isset($_GET['edit']) ? 'edit' : 'add'; ?>">
        <?php
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
        
        <?php if ($article): ?>
            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
        <?php endif; ?>

        <div>
            <label for="title">Název článku:</label>
            <input type="text" id="title" name="title" value="<?php echo $article ? htmlspecialchars($article['title']) : ''; ?>" required>
        </div>

        <div>
            <label for="perex">Perex:</label>
            <textarea id="perex" name="perex" required><?php echo $article ? htmlspecialchars($article['perex']) : ''; ?></textarea>
        </div>

        <div>
            <label for="content">Obsah:</label>
            <textarea id="content" name="content" class="editor" required><?php echo $article ? htmlspecialchars($article['content']) : ''; ?></textarea>
        </div>

        <div>
            <label>
                <input type="checkbox" name="published" <?php echo ($article && $article['published']) ? 'checked' : ''; ?>>
                Publikovat článek
            </label>
        </div>

        <button type="submit"><?php echo $article ? 'Upravit článek' : 'Přidat článek'; ?></button>
    </form>

    <!-- Seznam článků -->
    <h3>Seznam článků</h3>
    <div class="articles-list">
        <?php foreach ($articles as $article): ?>
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

<style>
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

.article-form input[type="text"],
.article-form textarea {
    width: 100%;
    padding: 8px;
}

.article-form textarea.editor {
    min-height: 300px;
}

.articles-list {
    margin-top: 20px;
}

.article-item {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
}

.article-item.draft {
    background: #fff5f5;
}

.article-item.published {
    background: #f5fff5;
}

.article-meta {
    font-size: 0.9em;
    color: #666;
    margin: 5px 0;
}

.article-actions {
    margin-top: 10px;
}
</style>