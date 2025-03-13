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

// Načtení dat uživatele a stylů
$userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);
$availableStyles = ['1' => 'Základní', '2' => 'Tmavý', '3' => 'Světlý'];

// Najít aktuálního uživatele
$currentUser = null;
foreach ($userData as &$user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUser = &$user;
        break;
    }
}

// Zpracování formulářů
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'change_style':
            $currentUser['settings']['theme'] = $_POST['style'];
            $styles['currentStyle'] = $_POST['style'];
            
            // Uložení změn
            file_put_contents(getFilePath('data', 'styles.json'), json_encode($styles, JSON_PRETTY_PRINT));
            file_put_contents(getFilePath('data', 'users.json'), json_encode($userData, JSON_PRETTY_PRINT));
            $success = "Vzhled byl úspěšně změněn.";
            break;
            
        case 'update_profile':
            if (!empty($_POST['new_password'])) {
                if (password_verify($_POST['current_password'], $currentUser['password'])) {
                    if ($_POST['new_password'] === $_POST['confirm_password']) {
                        $currentUser['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    } else {
                        $error = "Nová hesla se neshodují.";
                    }
                } else {
                    $error = "Nesprávné současné heslo.";
                }
            }
            
            $currentUser['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            // Uložení změn
            if (!isset($error)) {
                file_put_contents(getFilePath('data', 'users.json'), json_encode($userData, JSON_PRETTY_PRINT));
                $success = "Profil byl úspěšně aktualizován.";
            }
            break;
    }
}
?>

<!-- HTML část -->
<?php include(getFilePath('includes', 'header.php')); ?>

<main class="container">
    <section class="profile-section">
        <h2>Můj profil - <?php echo htmlspecialchars($currentUser['username']); ?></h2>
        
        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Změna vzhledu -->
        <div class="settings-box">
            <h3>Výběr vzhledu</h3>
            <form method="POST" class="settings-form">
                <input type="hidden" name="action" value="change_style">
                <div class="form-group">
                    <label for="style">Vzhled stránek:</label>
                    <select name="style" id="style">
                        <?php foreach ($availableStyles as $id => $name): ?>
                            <option value="<?php echo $id; ?>" 
                                    <?php echo ($styles['currentStyle'] == $id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Uložit vzhled</button>
            </form>
        </div>

        <!-- Úprava profilu -->
        <div class="settings-box">
            <h3>Úprava údajů</h3>
            <form method="POST" class="settings-form">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="current_password">Současné heslo:</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">Nové heslo:</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Potvrzení hesla:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                
                <button type="submit">Uložit změny</button>
            </form>
        </div>
    </section>
</main>

<?php include(getFilePath('includes', 'footer.php')); ?>