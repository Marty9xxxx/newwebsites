<?php
session_start();

// Připojení k DB
require '_DIR_.';  // nebo cesta k souboru s připojením

// Pokud uživatel již je přihlášen, přesměrujeme ho na admin stránku
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Ověření, zda hesla souhlasí
    if ($password != $confirm_password) {
        $error = "Hesla se neshodují.";
    } else {
        // Hashování hesla
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kontrola, zda uživatel již existuje
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        if ($stmt->rowCount() > 0) {
            $error = "Uživatelské jméno již existuje.";
        } else {
            // Vložení nového uživatele do databáze
            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            if ($stmt->execute(['username' => $username, 'password' => $hashed_password])) {
                // Přesměrování po úspěšné registraci
                header("Location: login.php");
                exit;
            } else {
                $error = "Něco se pokazilo při registraci.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
</head>
<body>
    <h2>Registrace nového uživatele</h2>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label for="username">Uživatelské jméno:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="confirm_password">Potvrďte heslo:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit">Registrovat</button>
    </form>

    <p>Máte již účet? <a href="login.php">Přihlaste se zde</a></p>
</body>
</html>
