<?php
require_once dirname(__DIR__) . '/config.php';
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Novinky</title>
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="content">
            <h2>Novinky</h2>
            <div class="news-list">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <div class="news-item">
                            <div class="news-header">
                                <span class="news-date"><?php echo htmlspecialchars($item['datetime']); ?></span>
                                <span class="news-author"><?php echo htmlspecialchars($item['author']); ?></span>
                            </div>
                            <div class="news-text"><?php echo htmlspecialchars($item['text']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Žádné novinky k zobrazení.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>