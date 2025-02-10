<?php
session_start();
if ($_POST['password'] == 'tajneheslo') {
    $_SESSION['admin'] = true;
} elseif ($_POST['password'] && $_POST['password'] != 'tajneheslo') {
    echo "Chybné heslo!";
}

if (!isset($_SESSION['admin'])) {
    echo '<form method="post" action="admin.php">
            <label for="password">Heslo:</label>
            <input type="password" name="password">
            <button type="submit">Přihlásit</button>
          </form>';
    exit;
}
?>
<link rel="stylesheet" href="style1.css">
<?php include 'header.php'; ?>
<main>
    <section class="content">
    <h2>Administrace</h2>
    <p>Tady můžete spravovat obsah webu.</p>
    </section>


<?php include 'footer.php'; ?>
