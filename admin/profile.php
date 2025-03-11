<?php
// Načtení konfigurace a pomocných funkcí
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/debug_helper.php';

// Kontrola přihlášení
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ' . getWebPath('admin/login.php'));
    exit;
}

// Načtení aktuálního uživatele
$userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);

// Debug výpis pro kontrolu dat
debugLog('Načtená uživatelská data:', 'DEBUG');
debugLog($userData, 'DEBUG');

// Kontrola struktury dat
if (!is_array($userData)) {
    debugLog('Chyba: userData není pole', 'ERROR');
    $error = "Chyba při načítání uživatelských dat.";
} else {
    $currentUser = null;
    foreach ($userData as &$user) {
        if ($user['username'] === $_SESSION['username']) {
            $currentUser = &$user;
            
            // Inicializace nastavení, pokud neexistují
            if (!isset($user['settings'])) {
                $user['settings'] = [
                    'editor' => 'simple',
                    'theme' => 'default'
                ];
            }
            
            // Zpracování formuláře pro změnu editoru
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_editor') {
                $user['settings']['editor'] = $_POST['editor'];
                
                // Uložení změn do JSON
                if (file_put_contents(
                    getFilePath('data', 'users.json'),
                    json_encode($userData, JSON_PRETTY_PRINT)
                )) {
                    $success = "Editor byl úspěšně změněn!";
                    debugLog('Nastavení editoru uloženo: ' . $_POST['editor']);
                } else {
                    $error = "Nepodařilo se uložit změny.";
                    debugLog('Chyba při ukládání nastavení editoru', 'ERROR');
                }
            }
            break;
        }
    }
}
?>

<!-- HTML část zůstává stejná, jen upravíme použití proměnné -->
<div class="editor-selection">
    <h3>Nastavení editoru</h3>
    
    <?php if ($currentUser): ?>
    <form method="POST" class="settings-form">
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
        
        <button type="submit" class="button">Uložit nastavení</button>
    </form>
    <?php else: ?>
    <div class="error-message">Uživatel nebyl nalezen.</div>
    <?php endif; ?>
    
    <!-- Zobrazení zpráv -->
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
</div>

<!-- Přidáme styly -->
<style>
.editor-selection {
    margin: 20px 0;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 4px;
}

.settings-form {
    max-width: 400px;
}

.success-message {
    margin-top: 10px;
    padding: 10px;
    background: #d4edda;
    color: #155724;
    border-radius: 4px;
}
</style>