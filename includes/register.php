<?php
// ====== INICIALIZACE SESSION ======
// Spuštění session pro práci s přihlášením
session_start();

// ====== NAČTENÍ KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
// Pokud je uživatel již přihlášen, přesměrujeme ho na hlavní stránku
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ' . getWebPath('index.php'));
    exit;
}

// ====== INICIALIZACE PROMĚNNÝCH ======
// Proměnné pro ukládání chybových a úspěšných zpráv
$error = '';
$success = '';

// ====== ZPRACOVÁNÍ FORMULÁŘE ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Načtení existujících uživatelů z JSON souboru
    $users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
    
    // Získání a očištění dat z formuláře
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $email = trim($_POST['email']);
    
    // ====== VALIDACE VSTUPNÍCH DAT ======
    if (strlen($username) < 3) {
        $error = "Uživatelské jméno musí mít alespoň 3 znaky.";
    } elseif (strlen($password) < 6) {
        $error = "Heslo musí mít alespoň 6 znaků.";
    } elseif ($password !== $password_confirm) {
        $error = "Hesla se neshodují.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Neplatná emailová adresa.";
    } else {
        // Kontrola existence uživatele
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
            // ====== VYTVOŘENÍ NOVÉHO UŽIVATELE ======
            $new_user = [
                'id' => count($users) + 1,  // Generování ID
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT), // Bezpečné hashování hesla
                'email' => $email,
                'role' => 'user',  // Výchozí role pro nové uživatele
                'created_at' => date('Y-m-d H:i:s') // Časová značka vytvoření
            ];
            
            // Přidání nového uživatele do pole
            $users[] = $new_user;
            
            // Uložení aktualizovaného seznamu uživatelů
            if (file_put_contents(getFilePath('data', 'users.json'), 
                                json_encode($users, JSON_PRETTY_PRINT))) {
                $success = "Registrace proběhla úspěšně! Nyní se můžete přihlásit.";
                // Přesměrování na přihlášení po 2 sekundách
                header("refresh:2;url=login.php");
            } else {
                $error = "Chyba při ukládání dat.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <title>Registrace</title>
    <!-- Načtení společné hlavičky -->
    <?php include(getFilePath('includes','header.php')); ?>
    
    <!-- ====== STYLY ====== -->
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- Vlastní styly pro formulář -->
    <style>
        .register-form {
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
        
        .error { 
            color: #dc3545;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #dc3545;
            border-radius: 4px;
        }
        
        .success { 
            color: #28a745;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #28a745;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <main>
        <section class="content">
            <h2>Registrace nového uživatele</h2>
            
            <!-- Zobrazení chybových hlášek -->
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Zobrazení úspěšných hlášek -->
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <!-- Registrační formulář -->
            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="username">Uživatelské jméno:</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           minlength="3"
                           value="<?php echo htmlspecialchars($username ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required
                           value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Heslo:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Potvrzení hesla:</label>
                    <input type="password" 
                           id="password_confirm" 
                           name="password_confirm" 
                           required>
                </div>
                
                <button type="submit" class="button">Registrovat</button>
            </form>
            
            <!-- Odkaz na přihlášení -->
            <p class="login-link">
                Již máte účet? 
                <a href="<?php echo getWebPath('includes/login.php'); ?>">
                    Přihlaste se
                </a>
            </p>
        </section>
    </main>
    
    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes', 'footer.php')); ?>
</body>
</html>