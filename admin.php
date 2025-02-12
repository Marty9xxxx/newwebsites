<?php
session_start();

// Kontrola přihlášení
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Kód pro administraci
echo "Vítejte v administraci, " . $_SESSION['username'] . "!";

// Odhlášení
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>



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
