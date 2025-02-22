<?php
// register.php
session_start();

// Pokud je uživatel již přihlášen, přesměrujeme ho
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načteme existující uživatele
    $users = json_decode(file_get_contents('data/users.json'), true);
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $email = trim($_POST['email']);
    
    // Základní validace
    if (strlen($username) < 3) {
        $error = "Uživatelské jméno musí mít alespoň 3 znaky.";
    } elseif (strlen($password) < 6) {
        $error = "Heslo musí mít alespoň 6 znaků.";
    } elseif ($password !== $password_confirm) {
        $error = "Hesla se neshodují.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Neplatná emailová adresa.";
    } else {
        // Kontrola, zda uživatel již neexistuje
        $user_exists = false;
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $user_exists = true;
                break;
            }
        }
        
        if ($user_exists) {
            $error = "Uživatelské jméno již existuje.";
        } else {
            // Vytvoření nového uživatele
            $new_user = [
                'id' => count($users) + 1,  // Jednoduché generování ID
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'role' => 'user',  // Výchozí role
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Přidáme nového uživatele
            $users[] = $new_user;
            
            // Uložíme aktualizovaný seznam uživatelů
            if (file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT))) {
                $success = "Registrace proběhla úspěšně! Nyní se můžete přihlásit.";
                // Přesměrujeme na login po 2 sekundách
                header("refresh:2;url=login.php");
            } else {
                $error = "Chyba při ukládání dat.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrace</title>
    <?php include 'header.php'; ?>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Registrace nového uživatele</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Uživatelské jméno:</label>
            <input type="text" name="username" required minlength="3">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Heslo:</label>
            <input type="password" name="password" required minlength="6">
        </div>
        <div>
            <label>Potvrzení hesla:</label>
            <input type="password" name="password_confirm" required>
        </div>
        <button type="submit">Registrovat</button>
    </form>
    
    <p>Již máte účet? <a href="login.php">Přihlaste se</a></p>
    <?php include 'footer.php'; ?>
</body>
</html>