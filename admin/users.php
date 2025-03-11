<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ A ROLE ======
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . getWebPath('admin/login.php'));
    exit;
}

// ====== NAČTENÍ UŽIVATELŮ ======
$userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);

// Kontrola struktury dat - pokud data nejsou v poli 'users', jsou přímo v kořeni
$users = is_array($userData) ? $userData : [];

// Debug výpis pro kontrolu
error_log('Loaded users data: ' . print_r($users, true));

// ====== ZPRACOVÁNÍ FORMULÁŘŮ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // ---- ÚPRAVA UŽIVATELE ----
            case 'edit':
                $userId = (int)$_POST['user_id'];
                // Najdeme uživatele podle ID
                foreach ($users as $key => $user) {
                    if (isset($user['id']) && $user['id'] === $userId) {
                        // Aktualizace údajů
                        $users[$key]['username'] = $_POST['username'];
                        $users[$key]['role'] = $_POST['role'];
                        
                        // Změna hesla pouze pokud bylo zadáno
                        if (!empty($_POST['password'])) {
                            $users[$key]['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        }
                        
                        // Uložení změn
                        if (file_put_contents(
                            getFilePath('data', 'users.json'),
                            json_encode($users, JSON_PRETTY_PRINT)
                        )) {
                            $_SESSION['message'] = 'Uživatel byl úspěšně upraven.';
                        } else {
                            $_SESSION['error'] = 'Nepodařilo se uložit změny.';
                        }
                        break;
                    }
                }
                break;

            // ---- SMAZÁNÍ UŽIVATELE ----
            case 'delete':
                $userId = $_POST['user_id'];
                if (isset($users[$userId])) {
                    // Odstranění uživatele z pole
                    unset($users[$userId]);
                    // Uložení aktualizovaného seznamu
                    file_put_contents(getFilePath('data', 'users.json'), 
                                   json_encode($users, JSON_PRETTY_PRINT));
                    $_SESSION['message'] = 'Uživatel byl úspěšně smazán.';
                }
                break;

            // ---- PŘIDÁNÍ UŽIVATELE ----
            case 'add':
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    // Vytvoření nového uživatele
                    $newUser = [
                        'username' => $_POST['username'],
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                        'role' => $_POST['role']
                    ];
                    // Přidání do pole uživatelů
                    $users[] = $newUser;
                    // Uložení aktualizovaného seznamu
                    file_put_contents(getFilePath('data', 'users.json'), 
                                   json_encode($users, JSON_PRETTY_PRINT));
                    $_SESSION['message'] = 'Nový uživatel byl úspěšně přidán.';
                }
                break;
        }
        // Přesměrování zpět na seznam uživatelů
        header('Location: admin.php?section=users');
        exit;
    }
}
?>

<!-- ====== HTML STRUKTURA ====== -->
<section class="content">
    <h2>Správa uživatelů</h2>

    <!-- Zobrazení zprávy o úspěchu/chybě -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
        <?php unset($_SESSION['message']); // Vymazání zprávy po zobrazení ?>
    <?php endif; ?>

    <!-- ====== FORMULÁŘ PRO NOVÉHO UŽIVATELE ====== -->
    <h3>Přidat nového uživatele</h3>
    <form method="post" class="user-form">
        <input type="hidden" name="action" value="add">
        <!-- Pole pro uživatelské jméno -->
        <div>
            <label for="new-username">Uživatelské jméno:</label>
            <input type="text" id="new-username" name="username" required>
        </div>
        <!-- Pole pro heslo -->
        <div>
            <label for="new-password">Heslo:</label>
            <input type="password" id="new-password" name="password" required>
        </div>
        <!-- Výběr role -->
        <div>
            <label for="new-role">Role:</label>
            <select id="new-role" name="role">
                <option value="user">Uživatel</option>
                <option value="admin">Administrátor</option>
            </select>
        </div>
        <button type="submit">Přidat uživatele</button>
    </form>

    <!-- ====== SEZNAM UŽIVATELŮ ====== -->
    <h3>Seznam uživatelů</h3>
    <div class="users-list">
        <?php foreach ($users as $id => $user): ?>
            <div class="user-item">
                <!-- Formulář pro úpravu uživatele -->
                <form method="post" class="user-form">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                    <!-- Pole pro úpravu jména -->
                    <div>
                        <label>Uživatelské jméno:</label>
                        <input type="text" name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <!-- Pole pro změnu hesla -->
                    <div>
                        <label>Nové heslo:</label>
                        <input type="password" name="password" 
                               placeholder="Ponechte prázdné pro zachování">
                    </div>
                    <!-- Výběr role -->
                    <div>
                        <label>Role:</label>
                        <select name="role">
                            <option value="user" 
                                    <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>
                                Uživatel
                            </option>
                            <option value="admin" 
                                    <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>
                                Administrátor
                            </option>
                        </select>
                    </div>
                    <!-- Tlačítka akcí -->
                    <div class="user-actions">
                        <button type="submit">Uložit změny</button>
                        <button type="submit" name="action" value="delete" 
                                onclick="return confirm('Opravdu chcete smazat tohoto uživatele?')">
                            Smazat
                        </button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====== CSS STYLY ====== -->
<style>
/* Základní styl formuláře */
.user-form {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
}

/* Odsazení prvků formuláře */
.user-form div {
    margin-bottom: 10px;
}

/* Zarovnání popisků */
.user-form label {
    display: inline-block;
    width: 150px;
}

/* Tlačítka akcí */
.user-actions {
    margin-top: 10px;
}

/* Seznam uživatelů */
.users-list {
    margin-top: 20px;
}

/* Jednotlivý uživatel */
.user-item {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    background: #f9f9f9;
}
</style>
