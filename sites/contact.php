<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== ZPRACOVÁNÍ FORMULÁŘE ======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ověření a očištění vstupních dat
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = trim($_POST['message']); // Odstranění mezer na začátku a konci
    $subject = trim($_POST['subject']);
    
    // Validace vstupních dat
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message) && !empty($subject)) {
        // Nastavení hlaviček e-mailu
        $to = 'your-email@example.com'; // Změňte na vaši e-mailovou adresu
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Pokus o odeslání e-mailu
        if (mail($to, $subject, $message, $headers)) {
            $success = "Děkujeme za zprávu, odpovíme co nejdříve.";

            // ====== UKLÁDÁNÍ DO JSON ======
            // Příprava dat pro uložení
            $emailData = [
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Cesta k souboru s e-maily
            $emailsFile = getFilePath('data', 'emails.json');
            
            // Načtení existujících e-mailů nebo vytvoření nového pole
            if (file_exists($emailsFile)) {
                $emails = json_decode(file_get_contents($emailsFile), true);
            } else {
                $emails = [];
            }
            
            // Přidání nového e-mailu a uložení
            $emails[] = $emailData;
            file_put_contents($emailsFile, json_encode($emails, JSON_PRETTY_PRINT));
        } else {
            $error = "Nastala chyba při odesílání e-mailu. Zkuste to prosím znovu.";
        }
    } else {
        $error = "Prosím vyplňte všechna pole správně.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- ====== META INFORMACE ====== -->
    <title>Napište mi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ====== NAČTENÍ STYLŮ ====== -->
    <?php include(getFilePath('includes','header.php')); ?>
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    
    <!-- ====== VLASTNÍ STYLY ====== -->
    <style>
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-group textarea {
            height: 150px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <main>
        <section class="content">
            <h2>Napište mi</h2>
            
            <!-- Zobrazení zpětné vazby -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Kontaktní formulář -->
            <form method="post" action="contact.php" class="contact-form">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Předmět:</label>
                    <input type="text" 
                           id="subject" 
                           name="subject" 
                           required
                           value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="message">Zpráva:</label>
                    <textarea id="message" 
                              name="message" 
                              required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="button">Odeslat</button>
            </form>
        </section>
    </main>

    <!-- ====== PATIČKA ====== -->
    <?php include(getFilePath('includes','footer.php')); ?>
</body>
</html>