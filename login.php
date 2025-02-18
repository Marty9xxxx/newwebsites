<?php
// login.php
session_start();

// Pokud je uživatel již přihlášen, přesměrujeme ho
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Zpracování přihlašovacího formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načteme data uživatelů z JSON souboru
    $users = json_decode(file_get_contents('data/users.json'), true);
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Procházíme uživatele a hledáme shodu
    $user_found = false;
    foreach ($users as $user) {
        // Kontrola uživatelského jména a hesla
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Uživatel nalezen a heslo souhlasí
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Přesměrování podle role
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    }
    
    // Pokud se dostaneme sem, přihlášení selhalo
    $error = "Nesprávné přihlašovací údaje!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Přihlášení</title>
</head>
<body>
    <h2>Přihlášení</h2>
    
    <?php if (isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Uživatelské jméno:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Heslo:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Přihlásit</button>
    </form>
    <a href="register.php">Registrovat se</a>
<?php include 'footer.php'; ?>

</body>
</html>