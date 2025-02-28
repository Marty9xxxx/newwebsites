<?php
// Spuštění session pro práci s přihlášením
session_start();

// Načtení konfiguračního souboru s funkcemi pro práci s cestami
require_once dirname(__DIR__) . '/config.php';

// Kontrola přihlášení a role uživatele
// Pokud není uživatel přihlášen nebo není admin, přesměruj na přihlášení
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ' . getWebPath('includes/login.php'));
    exit;
}

// Zpracování odhlášení uživatele
// Při kliknutí na odhlásit zruší session a přesměruje na přihlášení
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ' . getWebPath('includes/login.php'));
    exit;
}

// Načtení všech potřebných dat z JSON souborů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);
$guestbook = json_decode(file_get_contents(getFilePath('data', 'guestbook.json')), true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrace</title>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h2>Administrace</h2>
            <p>Vítejte, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Admin menu -->
            <div class="admin-menu">
                <h3>Správa obsahu</h3>
                <ul>
                    <li><a href="admin.php?section=news">Správa novinek</a></li>
                    <li><a href="admin.php?section=guestbook">Správa návštěvní knihy</a></li>
                    <li><a href="admin.php?section=articles">Správa článků</a></li>
                    <li><a href="admin.php?section=users">Správa uživatelů</a></li>
                    <li><a href="admin.php?section=styles">Vzhled webu</a></li>
                    <li><a href="admin.php?section=content">Správa obsahu</a></li>
                </ul>
            </div>

            <!-- Obsah podle sekce -->
            <?php
            if (isset($_GET['section'])) {
                $section = $_GET['section'];
                switch($section) {
                    case 'news':
                        include(getFilePath('admin', 'news.php'));
                        break;
                    case 'guestbook':
                        include(getFilePath('admin', 'guestbook.php'));
                        break;
                    case 'articles':
                            include(getFilePath('admin', 'articles.php'));
                            break;
                    case 'styles':
                        include(getFilePath('admin', 'styles.php'));
                        break;
                    case 'users':
                        include(getFilePath('admin', 'users.php'));
                        break;
                    case 'articles':
                        include(getFilePath('admin', 'articles.php'));
                        break;
                    case 'content':
                        include(getFilePath('admin', 'content.php'));
                        break;
                }
            }
            ?>
        </section>
    </main>

    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
