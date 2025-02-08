<?php
session_start();

// Připojení k databázi
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ověření uživatele
if ($_POST['username'] && $_POST['password']) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $_POST['username']]);
    $user = $stmt->fetch();

    // Ověření správnosti hesla
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['loggedin'] = true;
        header("Location: admin.php"); // Přesměrování na admin
    } else {
        echo "Chybné uživatelské jméno nebo heslo!";
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
