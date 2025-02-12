<?php
session_start(); // Začínáme session

// Ověření, jestli je uživatel přihlášen a má roli admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    // Přesměrování na login, pokud uživatel není přihlášen nebo nemá roli admin
    header('Location: login.php');
    exit;
}

echo "Vítejte v administraci, " . $_SESSION['username'] . "!"; // Zobrazíme uživatelské jméno
?>

<a href="admin.php?logout=true">Odhlásit se</a>

<link rel="stylesheet" href="style1.css">
<?php include 'header.php'; ?>
<main>
    <section class="content">
    <h2>Administrace</h2>
    <p>Tady můžete spravovat obsah webu.</p>
    <ul>
        <li><a href="news.php">Správa novinek</a></li>
        <li><a href="guestbook.php">Správa návštěvní knihy</a></li>
        <li><a href="admin.php?logout=true">Odhlásit se</a></li>
    </ul>
    </section>


<?php include 'footer.php'; ?>
