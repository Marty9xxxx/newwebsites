<?php
// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== NAČTENÍ ČLÁNKŮ ======
// Načtení JSON souboru s články a převod na PHP pole
// Operátor ?? poskytuje výchozí prázdné pole, pokud klíč 'articles' neexistuje
$articlesData = json_decode(file_get_contents(getFilePath('data', 'articles.json')), true);
$articles = $articlesData['articles'] ?? [];

// ====== ZPRACOVÁNÍ PARAMETRŮ ======
// Získání ID článku z URL parametru
// Použití operátoru ?? pro výchozí null hodnotu
$article_id = $_GET['id'] ?? null;
$article = null;

// ====== VYHLEDÁNÍ ČLÁNKU ======
// Pokud bylo zadáno ID, vyhledáme odpovídající článek
if ($article_id) {
    foreach ($articles as $a) {
        // Kontrola ID a stavu publikace
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
    <!-- ====== META INFORMACE ====== -->
    <!-- Dynamický titulek - buď název článku nebo obecný název sekce -->
    <title><?php echo $article ? htmlspecialchars($article['title']) : 'Články'; ?></title>
    
    <!-- ====== STYLY ====== -->
    <!-- Načtení speciálních stylů pro články -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/articles.css'); ?>">
    <!-- Vložení společné hlavičky -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="content">
            <?php if ($article_id): ?>
                <!-- ====== DETAIL ČLÁNKU ====== -->
                <?php if ($article): ?>
                    <article>
                        <!-- Nadpis článku -->
                        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                        
                        <!-- Metadata článku (autor, datum) -->
                        <div class="article-meta">
                            Autor: <?php echo htmlspecialchars($article['author']); ?> |
                            Publikováno: <?php echo htmlspecialchars($article['datetime']); ?>
                        </div>
                        
                        <!-- Perex - úvodní text článku -->
                        <div class="article-perex">
                            <?php echo htmlspecialchars($article['perex']); ?>
                        </div>
                        
                        <!-- Hlavní obsah článku -->
                        <div class="article-content">
                            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                        </div>
                        
                        <!-- Navigační odkaz zpět -->
                        <p><a href="articles.php">← Zpět na seznam článků</a></p>
                    </article>
                <?php else: ?>
                    <!-- Článek nebyl nalezen -->
                    <h1>Článek nenalezen</h1>
                    <p>Požadovaný článek neexistuje nebo není publikován.</p>
                    <p><a href="articles.php">← Zpět na seznam článků</a></p>
                <?php endif; ?>
            <?php else: ?>
                <!-- ====== SEZNAM ČLÁNKŮ ====== -->
                <h1>Články</h1>
                <div class="articles-list">
                    <?php 
                    // Proměnná pro sledování, zda existují nějaké články
                    $hasArticles = false;
                    foreach ($articles as $a): 
                        // Zobrazení pouze publikovaných článků
                        if ($a['published']):
                            $hasArticles = true;
                    ?>
                        <!-- Náhled jednotlivého článku -->
                        <article class="article-preview">
                            <!-- Nadpis s odkazem na detail -->
                            <h2>
                                <a href="articles.php?id=<?php echo $a['id']; ?>">
                                    <?php echo htmlspecialchars($a['title']); ?>
                                </a>
                            </h2>
                            
                            <!-- Metadata článku -->
                            <div class="article-meta">
                                Autor: <?php echo htmlspecialchars($a['author']); ?> |
                                Publikováno: <?php echo date('d.m.Y H:i', strtotime($a['datetime'])); ?>
                            </div>
                            
                            <!-- Perex článku -->
                            <div class="article-perex">
                                <?php echo htmlspecialchars($a['perex']); ?>
                            </div>
                            
                            <!-- Odkaz na celý článek -->
                            <div class="article-actions">
                                <a href="articles.php?id=<?php echo $a['id']; ?>" class="read-more">
                                    Číst více →
                                </a>
                            </div>
                        </article>
                    <?php endif; endforeach; ?>
                    
                    <!-- Zpráva, pokud nejsou žádné články -->
                    <?php if (!$hasArticles): ?>
                        <p>Žádné články k zobrazení.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>