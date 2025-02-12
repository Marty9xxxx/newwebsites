<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = "user"; // Výchozí role

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents("../data/data.txt", "$username|$hashed_password|$role\n", FILE_APPEND);
        header("Location: login.php");
        exit;
    }
    $error = "Vyplňte všechna pole!";
}
?>
<?php include 'header.php'; ?>
<form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Uživatelské jméno" required>
    <input type="password" name="password" placeholder="Heslo" required>
    <button type="submit">Registrovat</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="login.php">Zpět na přihlášení</a>
<?php include 'footer.php'; ?>