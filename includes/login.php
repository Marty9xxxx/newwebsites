<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
// Pokud je uživatel již přihlášen, přesměrujeme ho
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ' . getWebPath('index.php'));
    exit;
}

// ====== ZPRACOVÁNÍ PŘIHLÁŠENÍ ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kontrola, zda byly odeslány přihlašovací údaje
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $error = "Vyplňte prosím všechna pole!";
    } else {
        // Načtení uživatelských dat z JSON
        $userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
        $users = $userData['users'];
        
        // Debug výpis
        error_log('Login attempt for: ' . $_POST['username']);
        
        // Vyhledání uživatele a ověření hesla
        $user_found = false;
        foreach ($users as $user) {
            if ($user['username'] === $_POST['username']) {
                if (password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    error_log('Login successful for: ' . $user['username']);
                    header('Location: ' . getWebPath('index.php'));
                    exit;
                }
            }
        }
        
        // Pokud se nepodařilo přihlásit
        $error = "Nesprávné přihlašovací údaje!";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <title>Přihlášení</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ====== NAČTENÍ HLAVIČKY ====== -->
    <?php include(getFilePath('includes', 'header.php')); ?>
    
    <!-- ====== VLASTNÍ STYLY ====== -->
    <style>
        /* Styly pro přihlašovací formulář */
        .login-form {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <main>
        <section class="content">
            <h2>Přihlášení</h2>
            
            <!-- Zobrazení chybové hlášky -->
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Přihlašovací formulář -->
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Uživatelské jméno:</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Heslo:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required>
                </div>
                <button type="submit" class="button">Přihlásit</button>
                
                <!-- Odkaz na registraci -->
                <div class="register-link">
                    <a href="<?php echo getWebPath('includes/register.php'); ?>">
                        Registrovat se
                    </a>
                </div>
            </form>
        </section>
    </main>

    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>