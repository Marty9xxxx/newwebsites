<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $users = file( __DIR__ . "/data/data.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($users as $user) {
        list($stored_user, $stored_hash, $role) = explode('|', $user);
        
        if ($username === $stored_user && password_verify($password, $stored_hash)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: admin.php");
            exit;
        }
    }
    $error = "Neplatné přihlašovací údaje!";
}
?>
<?php include 'header.php'; ?>
<form action="login.php" method="POST">
    <input type="text" name="username" placeholder="Uživatelské jméno" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <button type="submit">Přihlásit</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="register.php">Registrovat se</a>
<?php include 'footer.php'; ?>
