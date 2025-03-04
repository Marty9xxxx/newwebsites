<?php
// ====== INICIALIZACE A KONFIGURACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi a editoru
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/simple_editor.php';

// ====== NAČTENÍ DAT ======
// Načtení potřebných JSON souborů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// ====== NASTAVENÍ NÁVŠTĚVNÍ KNIHY ======
// Cesta k souboru s příspěvky
$shoutbox_file = getFilePath('data', 'guestbook.json');
$max_messages = 50;  // Maximální počet uchovávaných zpráv
// Seznam zakázaných slov pro filtrování
$bad_words = ['blbost', 'hlupák', 'nadávka']; 
// Ochrana proti spamu - časový limit mezi zprávami
$last_post_time = $_SESSION['last_post_time'] ?? 0;
$spam_delay = 10;  // Minimální prodleva mezi zprávami v sekundách

// ====== POMOCNÉ FUNKCE ======
// Funkce pro nahrazení nevhodných slov hvězdičkami
function filter_bad_words($text, $bad_words) {
    return str_ireplace($bad_words, '****', $text);
}

// ====== ZPRACOVÁNÍ FORMULÁŘE ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                // Kontrola admin práv
                if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                    $error = "Nemáte oprávnění upravovat příspěvky!";
                    break;
                }
                
                $messageId = (int)$_POST['message_id'];
                $editedMessage = trim($_POST['message']);
                
                // Načtení a úprava zprávy
                $messages = json_decode(file_get_contents($shoutbox_file), true) ?: [];
                if (isset($messages[$messageId])) {
                    $messages[$messageId]['message'] = $editedMessage;
                    $messages[$messageId]['edited'] = true;
                    $messages[$messageId]['edited_by'] = $_SESSION['username'];
                    $messages[$messageId]['edited_at'] = date('Y-m-d H:i:s');
                    
                    file_put_contents($shoutbox_file, json_encode($messages, JSON_PRETTY_PRINT));
                    header('Location: admin.php?section=guestbook&success=2');
                    exit;
                }
                break;
                
            // ...existing case for adding new message...
        }
    }
    // Očištění vstupních dat
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    // Validace dat
    if (empty($name) || empty($message)) {
        $error = "Vyplňte jméno i zprávu!";
    }
    // Kontrola časového limitu proti spamu
    elseif (time() - $last_post_time < $spam_delay) {
        $error = "Posíláte zprávy příliš rychle! Zkuste to za pár sekund.";
    }
    else {
        // Aktualizace času poslední zprávy
        $_SESSION['last_post_time'] = time();
        // Filtrování nevhodných slov
        $message = filter_bad_words($message, $bad_words);
        
        // Načtení existujících zpráv
        $messages = json_decode(file_get_contents($shoutbox_file), true) ?: [];
        
        // Přidání nové zprávy na začátek
        array_unshift($messages, [
            'name' => $name,
            'message' => $message,
            'datetime' => date('Y-m-d H:i:s')
        ]);
        
        // Omezení počtu zpráv
        $messages = array_slice($messages, 0, $max_messages);
        
        // Uložení aktualizovaných zpráv
        file_put_contents($shoutbox_file, json_encode($messages, JSON_PRETTY_PRINT));
        
        // Přesměrování pro prevenci duplicitního odeslání
        header('Location: admin.php?section=guestbook&success=1');
        exit;
    }
}

// ====== NAČTENÍ ZPRÁV PRO ZOBRAZENÍ ======
$messages = json_decode(file_get_contents($shoutbox_file), true) ?: [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <?php include(getFilePath('includes', 'header.php')); ?> 
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/style1.css'); ?>">
    <link rel="stylesheet" href="<?php echo getWebPath('styles/print.css'); ?>" media="print">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Návštěvní kniha</title>
</head>
<body>

<!-- ====== STRUKTURA STRÁNKY ====== -->
<header>
    <h1>Návštěvní kniha</h1>
</header>

<div class="container">
    <main>
        <section class="content">
            <h2>Zanechte vzkaz</h2>

            <!-- Zobrazení chybových hlášek -->
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <!-- Potvrzení úspěšného odeslání -->
            <?php if (isset($_GET['success'])): ?>
                <p class="success">Zpráva byla úspěšně přidána.</p>
            <?php endif; ?>

            <!-- Formulář pro přidání zprávy -->
            <form method="post">
                <div>
                    <label for="name">Jméno:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="message">Zpráva:</label>
                    <?php
                    // Inicializace a vykreslení editoru přímo v místě textarea
                    $editor = new SimpleEditor('message');
                    $editor->render();
                    ?>
                </div>
                <button type="submit">Odeslat</button>
            </form>

            <!-- Seznam zpráv -->
            <h2>Poslední zprávy</h2>
            <div class="messages">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $index => $msg): ?>
                        <div class="message">
                            <!-- Bezpečný výpis dat pomocí htmlspecialchars -->
                            <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                            <span class="datetime"><?php echo htmlspecialchars($msg['datetime']); ?></span>
                            
                            <?php if (isset($msg['edited'])): ?>
                                <span class="edited-info">
                                    (Upraveno: <?php echo htmlspecialchars($msg['edited_by']); ?> 
                                    - <?php echo htmlspecialchars($msg['edited_at']); ?>)
                                </span>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <!-- Tlačítko pro editaci -->
                                <button onclick="showEditForm(<?php echo $index; ?>)" class="edit-btn">Upravit</button>
                                
                                <!-- Formulář pro editaci -->
                                <form method="post" class="edit-form" id="edit-form-<?php echo $index; ?>" style="display: none;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="message_id" value="<?php echo $index; ?>">
                                    <?php
                                    $editor = new SimpleEditor('message_' . $index, $msg['message']);
                                    $editor->render();
                                    ?>
                                    <button type="submit">Uložit změny</button>
                                    <button type="button" onclick="hideEditForm(<?php echo $index; ?>)">Zrušit</button>
                                </form>
                            <?php endif; ?>
                            
                            <p><?php echo SimpleEditor::parseContent($msg['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Zatím žádné zprávy.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<footer>
   <?php include(getFilePath('includes', 'footer.php')); ?> 
</footer>

<!-- Přidáme JavaScript pro ovládání editačních formulářů -->
<script>
function showEditForm(id) {
    document.getElementById('edit-form-' + id).style.display = 'block';
}

function hideEditForm(id) {
    document.getElementById('edit-form-' + id).style.display = 'none';
}
</script>

<!-- Přidáme styly -->
<style>
.edit-btn {
    float: right;
    padding: 5px 10px;
    background: #573232;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.edited-info {
    font-size: 0.8em;
    color: #666;
    font-style: italic;
}

.edit-form {
    margin: 10px 0;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 4px;
}
</style>

</body>
</html>