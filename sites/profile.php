<?php
// Načtení konfigurace a pomocných funkcí
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/debug_helper.php';

// Kontrola přihlášení (ale ne admin práv)
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ' . getWebPath('includes/login.php'));
    exit;
}

// Načtení aktuálního uživatele
$userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$currentUser = null;

foreach ($userData as &$user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUser = &$user;
        break;
    }
}

// Zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'change_editor':
            $currentUser['settings']['editor'] = $_POST['editor'];
            if (file_put_contents(
                getFilePath('data', 'users.json'),
                json_encode($userData, JSON_PRETTY_PRINT)
            )) {
                $success = "Nastavení bylo úspěšně uloženo.";
            }
            break;
            
        case 'change_password':
            if (password_verify($_POST['old_password'], $currentUser['password'])) {
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    $currentUser['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    if (file_put_contents(
                        getFilePath('data', 'users.json'),
                        json_encode($userData, JSON_PRETTY_PRINT)
                    )) {
                        $success = "Heslo bylo úspěšně změněno.";
                    }
                } else {
                    $error = "Nová hesla se neshodují.";
                }
            } else {
                $error = "Nesprávné současné heslo.";
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Můj profil</title>
    <?php include(getFilePath('includes', 'header.php')); ?>
</head>
<body>
    <main>
        <section class="profile-content">
            <h2>Můj profil - <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            
            <?php if (isset($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Nastavení editoru -->
            <div class="settings-section">
                <h3>Nastavení editoru</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="change_editor">
                    <div class="form-group">
                        <label for="editor">Preferovaný editor:</label>
                        <select name="editor" id="editor">
                            <option value="simple" <?php echo ($currentUser['settings']['editor'] ?? 'simple') === 'simple' ? 'selected' : ''; ?>>
                                Simple Editor (BB kódy)
                            </option>
                            <option value="tinymce" <?php echo ($currentUser['settings']['editor'] ?? 'simple') === 'tinymce' ? 'selected' : ''; ?>>
                                TinyMCE (Pokročilý)
                            </option>
                        </select>
                    </div>
                    <button type="submit">Uložit nastavení</button>
                </form>
            </div>

            <!-- Změna hesla -->
            <div class="settings-section">
                <h3>Změna hesla</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label for="old_password">Současné heslo:</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nové heslo:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Potvrďte nové heslo:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit">Změnit heslo</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>