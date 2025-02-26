<?php
// login.php
session_start();

// Load the configuration   
require_once dirname(__DIR__) . '/config.php';

// Pokud je uživatel již přihlášen, přesměrujeme ho
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ' . getWebPath('index.php'));
    exit;
}

// Zpracování přihlašovacího formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načteme data uživatelů z JSON souboru
    $users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
    
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
                header('Location: ' . getWebPath('admin/admin.php'));
            } else {
                header('Location: ' . getWebPath('index.php'));
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
    <?php include (getFilePath('includes','header.php')); ?>
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
    <a href="<?php echo getWebPath('includes/register.php'); ?>">Registrovat se</a>
    <?php include(getFilePath('includes', 'footer.php')); ?>

</body>
</html>