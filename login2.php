<?php
session_start(); // Začínáme session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $file = __DIR__ . '../data/data.txt'; // Cesta k souboru s uživatelskými údaji

    // Čteme data ze souboru
    $users = file($file, FILE_IGNORE_NEW_LINES);

    $is_authenticated = false;
    $user_role = '';

    // Procházíme všechny řádky v souboru a ověřujeme, zda se shoduje uživatel a heslo
    foreach ($users as $user) {
        list($stored_username, $stored_hashed_password, $stored_role) = explode('|', $user);

        if ($stored_username === $username && password_verify($password, $stored_hashed_password)) {
            $is_authenticated = true;
            $user_role = $stored_role; // Uložení role uživatele
            break;
        }
    }

    if ($is_authenticated) {
        // Uložení údajů do session pro pozdější ověření
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username; // Uložíme jméno uživatele do session
        $_SESSION['role'] = $user_role; // Uložíme roli uživatele do session
        header("Location: admin.php"); // Přesměrujeme na admin stránku
        exit;
    } else {
        echo "Špatné přihlašovací údaje!";
    }
}
?>
<?php include 'header.php'; ?>
<!-- Formulář pro přihlášení -->
<form action="login.php" method="POST">
    <label for="username">Uživatelské jméno:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Heslo:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Přihlásit se</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="register.php">Registrovat se</a>
<?php include 'footer.php'; ?>



