<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Při registraci přidáme roli (např. 'user', 'admin')
    $file = __DIR__ . '../data/data.txt'; // Cesta k souboru s uživatelskými údaji

    // Zkontrolujeme, jestli už uživatel neexistuje
    $users = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        list($stored_username, $stored_hashed_password, $stored_role) = explode('|', $user);
        if ($stored_username === $username) {
            echo "Uživatel s tímto jménem už existuje!";
            exit;
        }
    }

    // Hashujeme heslo a přidáme nového uživatele do souboru
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $new_user = $username . '|' . $hashed_password . '|' . $role . "\n";
    file_put_contents($file, $new_user, FILE_APPEND);

    echo "Registrace proběhla úspěšně!";
}
?>

<!-- Formulář pro registraci -->
<form action="register.php" method="POST">
    <label for="username">Uživatelské jméno:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Heslo:</label>
    <input type="password" id="password" name="password" required>

    <label for="role">Role (user nebo admin):</label>
    <input type="text" id="role" name="role" required>

    <button type="submit">Registrovat se</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>