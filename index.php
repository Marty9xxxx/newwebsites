<?php
session_start();
// Načtení konfigurace  
require_once 'config.php';

// Načtení dat z JSONů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// Načtení obsahu z JSON souboru
$content_file = getFilePath('data', 'content.json');
$content = json_decode(file_get_contents($content_file), true);

// Získání dat pro homepage
$homepage = $content['homepage'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($homepage['title'] ?? 'Úvodní stránka'); ?></title>
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <section class="search">
        <form action="<?php echo getWebPath('includes/search.php'); ?>" method="get">
            <input type="text" name="keywords" placeholder="Napište text" required>
            <button type="submit">Hledat</button>
        </form>
    </section>

    <main>
        <section class="content">
            <?php if ($homepage): ?>
                <h1><?php echo htmlspecialchars($homepage['title']); ?></h1>
                <div class="homepage-content">
                    <?php echo nl2br(htmlspecialchars($homepage['content'])); ?>
                </div>
                <div class="last-edit">
                    <small>
                        Naposledy upraveno: <?php echo date('d.m.Y H:i', strtotime($homepage['last_edited'])); ?>
                        (<?php echo htmlspecialchars($homepage['edited_by']); ?>)
                    </small>
                </div>
            <?php else: ?>
                <p>Obsah stránky není dostupný.</p>
            <?php endif; ?>
        </section>
    </main>
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
