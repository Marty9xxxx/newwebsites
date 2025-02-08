<?php
session_start();

// Připojení k DB
require 'db.php';  // nebo cesta k souboru s připojením

// Pokud je uživatel přihlášen, přesměrujeme ho na admin stránku
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Zkontroluj, jestli uživatel existuje v databázi
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pokud uživatel existuje, ověř heslo
    if ($user && password_verify($password, $user['password'])) {
        // Uložíme do session, že uživatel je přihlášený
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];

        // Přesměrujeme na admin stránku
        header("Location: admin.php");
        exit;
    } else {
        // Pokud je heslo špatně
        $error = "Nesprávné uživatelské jméno nebo heslo.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Přihlášení do administrace</h2>
    
    <?php if (isset($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="username">Uživatelské jméno:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Přihlásit</button>
    </form>

    <p>Nemáte účet? <a href="register.php">Zaregistrujte se zde</a></p>
</body>
</html>
