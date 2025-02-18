<?php
session_start();

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
$users = json_decode(file_get_contents('data/users.json'), true);
$guestbook = json_decode(file_get_contents('data/guestbook.json'), true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrace</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
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
                        include 'admin/news.php';
                        break;
                    case 'guestbook':
                        include 'admin/guestbook.php';
                        break;
                    case 'users':
                        include 'admin/users.php';
                        break;
                    case 'styles':
                        include 'admin/styles.php';
                        break;
                }
            }
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
