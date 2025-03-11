<?php
// ====== INICIALIZACE SESSION ======
session_start();

// ====== NAČTENÍ KONFIGURACE ======
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

// ====== ZPRACOVÁNÍ PŘIHLÁŠENÍ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načtení uživatelů
    $userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
    $users = $userData['users'];
    
    // Debug informace
    error_log('Attempting login for: ' . $_POST['username']);
    
    // Vyhledání uživatele
    foreach ($users as $user) {
        if ($user['username'] === $_POST['username']) {
            // Debug informace pro heslo
            error_log('Found user, verifying password');
            
            if (password_verify($_POST['password'], $user['password'])) {
                // Pouze admin může přistupovat do admin sekce
                if ($user['role'] === 'admin') {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    error_log('Admin login successful');
                    header('Location: admin.php');
                    exit;
                } else {
                    $error = "Nemáte oprávnění pro přístup do administrace!";
                }
            }
        }
    }
    
    if (!isset($error)) {
        $error = "Nesprávné přihlašovací údaje!";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Admin přihlášení</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="content">
            <h2>Přihlášení do administrace</h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Uživatelské jméno:</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Heslo:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Přihlásit se</button>
            </form>
        </section>
    </main>
    
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>