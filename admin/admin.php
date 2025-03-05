<?php
// Spuštění session pro práci s přihlášením - musí být před jakýmkoliv výstupem
ob_start(); // kontroluje výstup z php
session_start();

// Načtení konfiguračního souboru s funkcemi pro práci s cestami
// Použijeme dirname(__DIR__) pro získání nadřazené složky (root webu)
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘÍSTUPU ======
// Kontrola přihlášení a role uživatele
// Trojitá kontrola:
// 1. Existence session
// 2. Platnost přihlášení
// 3. Role admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ' . getWebPath('includes/login.php'));
    exit; // Ukončení skriptu pro zabránění dalšího vykonávání
}

// ====== ODHLÁŠENÍ ======
// Zpracování odhlášení uživatele
// Při kliknutí na odhlásit:
// 1. Vymaže všechny proměnné v session
// 2. Zruší celou session
// 3. Přesměruje na přihlašovací stránku
if (isset($_GET['logout'])) {
    session_unset(); // Vymaže proměnné
    session_destroy(); // Zruší session
    header('Location: ' . getWebPath('includes/login.php'));
    exit;
}

// ====== NAČTENÍ DAT ======
// Načtení všech potřebných dat z JSON souborů pro administraci
// Použití getFilePath pro bezpečné získání cest k souborům
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
$news = json_decode(file_get_contents(getFilePath('data', 'news.json')), true);
$guestbook = json_decode(file_get_contents(getFilePath('data', 'guestbook.json')), true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrace</title>
    <!-- Načtení základního stylu webu -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <!-- Vložení společné hlavičky -->
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="content">
            <h2>Administrace</h2>
            <!-- Přivítání přihlášeného uživatele s ochranou proti XSS -->
            <p>Vítejte, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            
            <!-- Zobrazení zprávy o úspěchu (např. po uložení změn) -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                <?php unset($_SESSION['message']); // Vymazání zprávy po zobrazení ?> 
            <?php endif; ?>

            <!-- ====== ADMINISTRAČNÍ MENU ====== -->
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

            <!-- ====== NAČÍTÁNÍ SEKCÍ ====== -->
            <!-- Podle parametru section se načte příslušná stránka -->
            <?php
            if (isset($_GET['section'])) {
                $section = $_GET['section'];
                switch($section) {
                    case 'news': // Správa novinek
                        include(getFilePath('admin', 'news.php'));
                        break;
                    case 'guestbook': // Správa návštěvní knihy
                        include(getFilePath('admin', 'guestbook.php'));
                        break;
                    case 'articles': // Správa článků
                        include(getFilePath('admin', 'articles.php'));
                        break;
                    case 'styles': // Nastavení vzhledu
                        include(getFilePath('admin', 'styles.php'));
                        break;
                    case 'users': // Správa uživatelů
                        include(getFilePath('admin', 'users.php'));
                        break;
                    case 'content': // Správa obsahu
                        include(getFilePath('admin', 'content.php'));
                        break;
                }
            }
            ?>
        </section>
    </main>

    <!-- Vložení společné patičky -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>
