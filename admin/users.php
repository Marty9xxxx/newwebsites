<?php
// Odstranit session_start() - session je již spuštěna v admin.php
require_once dirname(__DIR__) . '/config.php';

// Kontrola, zda je uživatel přihlášen jako admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . getWebPath('admin/login.php'));
    exit;
}

// Načtení seznamu uživatelů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);

// Zpracování formuláře pro úpravu uživatele
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                // Úprava existujícího uživatele
                $userId = $_POST['user_id'];
                if (isset($users[$userId])) {
                    $users[$userId]['username'] = $_POST['username'];
                    $users[$userId]['role'] = $_POST['role'];
                    // Změna hesla pouze pokud bylo zadáno nové
                    if (!empty($_POST['password'])) {
                        $users[$userId]['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }
                    file_put_contents(getFilePath('data', 'users.json'), json_encode($users, JSON_PRETTY_PRINT));
                    $_SESSION['message'] = 'Uživatel byl úspěšně upraven.';
                }
                break;

            case 'delete':
                // Smazání uživatele
                $userId = $_POST['user_id'];
                if (isset($users[$userId])) {
                    unset($users[$userId]);
                    file_put_contents(getFilePath('data', 'users.json'), json_encode($users, JSON_PRETTY_PRINT));
                    $_SESSION['message'] = 'Uživatel byl úspěšně smazán.';
                }
                break;

            case 'add':
                // Přidání nového uživatele
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    $newUser = [
                        'username' => $_POST['username'],
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                        'role' => $_POST['role']
                    ];
                    $users[] = $newUser;
                    file_put_contents(getFilePath('data', 'users.json'), json_encode($users, JSON_PRETTY_PRINT));
                    $_SESSION['message'] = 'Nový uživatel byl úspěšně přidán.';
                }
                break;
        }
        header('Location: admin.php?section=users');
        exit;
    }
}
?>

<section class="content">
    <h2>Správa uživatelů</h2>

    <!-- Zobrazení zprávy o úspěchu/chybě -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Formulář pro přidání nového uživatele -->
    <h3>Přidat nového uživatele</h3>
    <form method="post" class="user-form">
        <input type="hidden" name="action" value="add">
        <div>
            <label for="new-username">Uživatelské jméno:</label>
            <input type="text" id="new-username" name="username" required>
        </div>
        <div>
            <label for="new-password">Heslo:</label>
            <input type="password" id="new-password" name="password" required>
        </div>
        <div>
            <label for="new-role">Role:</label>
            <select id="new-role" name="role">
                <option value="user">Uživatel</option>
                <option value="admin">Administrátor</option>
            </select>
        </div>
        <button type="submit">Přidat uživatele</button>
    </form>

    <!-- Seznam existujících uživatelů -->
    <h3>Seznam uživatelů</h3>
    <div class="users-list">
        <?php foreach ($users as $id => $user): ?>
            <div class="user-item">
                <form method="post" class="user-form">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                    <div>
                        <label>Uživatelské jméno:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div>
                        <label>Nové heslo:</label>
                        <input type="password" name="password" placeholder="Ponechte prázdné pro zachování">
                    </div>
                    <div>
                        <label>Role:</label>
                        <select name="role">
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Uživatel</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrátor</option>
                        </select>
                    </div>
                    <div class="user-actions">
                        <button type="submit">Uložit změny</button>
                        <button type="submit" name="action" value="delete" 
                                onclick="return confirm('Opravdu chcete smazat tohoto uživatele?')">Smazat</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<style>
.user-form {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
}

.user-form div {
    margin-bottom: 10px;
}

.user-form label {
    display: inline-block;
    width: 150px;
}

.user-actions {
    margin-top: 10px;
}

.users-list {
    margin-top: 20px;
}

.user-item {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    background: #f9f9f9;
}
</style>
