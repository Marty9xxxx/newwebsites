<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        die("Vyplňte všechny údaje.");
    }

    $dataFile = __DIR__ . "/data/data.txt";

    // Ověření, zda uživatel už existuje
    if (file_exists($dataFile)) {
        $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($existingUser) = explode('|', $line);
            if ($existingUser === $username) {
                die("Uživatel už existuje!");
            }
        }
    }

    // Přidání nového uživatele do souboru
    $newEntry = "$username|$password\n"; // POZOR: Hesla by se měla hashovat!

    if (file_put_contents($dataFile, $newEntry, FILE_APPEND | LOCK_EX)) {
        echo "Registrace úspěšná! <a href='login.php'>Přihlásit se</a>";
    } else {
        echo "Chyba při ukládání dat.";
    }
}
?>
