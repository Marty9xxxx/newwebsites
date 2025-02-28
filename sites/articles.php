<?php
require_once dirname(__DIR__) . '/config.php';

// Načtení všech článků
$articlesData = json_decode(file_get_contents(getFilePath('data', 'articles.json')), true);
$articles = $articlesData['articles'] ?? [];

// Načtení článku podle ID, pokud je zadáno
$article_id = $_GET['id'] ?? null;
$article = null;

if ($article_id) {
    foreach ($articles as $a) {
        if ($a['id'] == $article_id && $a['published']) {
            $article = $a;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $article ? htmlspecialchars($article['title']) : 'Články'; ?></title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/articles.css'); ?>">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="content">
            <?php if ($article_id): ?>
                <!-- Zobrazení detailu článku -->
                <?php if ($article): ?>
                    <article>
                        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                        <div class="article-meta">
                            Autor: <?php echo htmlspecialchars($article['author']); ?> |
                            Publikováno: <?php echo htmlspecialchars($article['datetime']); ?>
                        </div>
                        <div class="article-perex">
                            <?php echo htmlspecialchars($article['perex']); ?>
                        </div>
                        <div class="article-content">
                            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                        </div>
                        <p><a href="articles.php">← Zpět na seznam článků</a></p>
                    </article>
                <?php else: ?>
                    <h1>Článek nenalezen</h1>
                    <p>Požadovaný článek neexistuje nebo není publikován.</p>
                    <p><a href="articles.php">← Zpět na seznam článků</a></p>
                <?php endif; ?>
            <?php else: ?>
                <!-- Zobrazení seznamu všech publikovaných článků -->
                <h1>Články</h1>
                <div class="articles-list">
                    <?php 
                    $hasArticles = false;
                    foreach ($articles as $a): 
                        if ($a['published']):
                            $hasArticles = true;
                    ?>
                        <article class="article-preview">
                            <h2>
                                <a href="articles.php?id=<?php echo $a['id']; ?>">
                                    <?php echo htmlspecialchars($a['title']); ?>
                                </a>
                            </h2>
                            <div class="article-meta">
                                Autor: <?php echo htmlspecialchars($a['author']); ?> |
                                Publikováno: <?php echo date('d.m.Y H:i', strtotime($a['datetime'])); ?>
                            </div>
                            <div class="article-perex">
                                <?php echo htmlspecialchars($a['perex']); ?>
                            </div>
                            <div class="article-actions">
                                <a href="articles.php?id=<?php echo $a['id']; ?>" class="read-more">
                                    Číst více →
                                </a>
                            </div>
                        </article>
                    <?php endif; endforeach; ?>
                    <?php if (!$hasArticles): ?>
                        <p>Žádné články k zobrazení.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>