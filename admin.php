<?php
session_start();
// Načtení konfigurace
require_once './config.php';

// Kontrola přihlášení a role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Zpracování odhlášení
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Načtení potřebných dat
$users = json_decode(file_get_contents(path('data', 'users.json')), true);
$styles = json_decode(file_get_contents(path('data', 'styles.json')), true);
$news = json_decode(file_get_contents(path('data', 'news.json')), true);
$guestbook = json_decode(file_get_contents(path('data', 'guestbook.json')), true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrace</title>
    <link rel="stylesheet" href="<?php echo path('styles', 'styles.css'); ?>">
    <?php include(path('includes', 'header.php')); ?>
</head>
<body>
    
    <main>
        <section class="content">
            <h2>Administrace</h2>
            <p>Vítejte, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            
            <!-- Admin menu -->
            <div class="admin-menu">
                <h3>Správa obsahu</h3>
                <ul>
                    <li><a href="admin.php?section=news">Správa novinek</a></li>
                    <li><a href="admin.php?section=guestbook">Správa návštěvní knihy</a></li>
                    <li><a href="admin.php?section=users">Správa uživatelů</a></li>
                    <li><a href="admin.php?section=styles">Vzhled webu</a></li>
                </ul>
            </div>

            <!-- Obsah podle sekce -->
            <?php
            if (isset($_GET['section'])) {
                $section = $_GET['section'];
                switch($section) {
                    case 'news':
                        include(path('admin', 'news.php'));
                        break;
                    case 'guestbook':
                        include(path('admin', 'guestbook.php'));
                        break;
                    case 'styles':
                        include(path('admin', 'styles.php'));
                        break;
                    case 'users':
                        include(path('admin', 'users.php'));
                        break;
                    case 'articles':
                        include(path('admin', 'articles.php'));
                        break;
                }
            }
            ?>
        </section>
    </main>

    <?php include(path('includes', 'footer.php')); ?>
</body>
</html>
