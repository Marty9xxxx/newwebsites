<?php
session_start();
// Načtení dat z JSONů
$users = json_decode(file_get_contents(path('data', 'users.json')), true);
$styles = json_decode(file_get_contents(path('data', 'styles.json')), true);

// Vložení hlavičky
include(path('includes', 'header.php'));

$shoutbox_file = '../data/shoutbox.txt'; // Uložení zpráv mimo veřejný adresář
$max_messages = 50; // Limit na počet zpráv
$bad_words = ['blbost', 'hlupák', 'nadávka']; // Seznam zakázaných slov
$last_post_time = $_SESSION['last_post_time'] ?? 0;
$spam_delay = 10; // Minimální čas mezi zprávami (v sekundách)

// Funkce pro filtrování nevhodných slov
function filter_bad_words($text, $bad_words) {
    return str_ireplace($bad_words, '****', $text);
}

// Zpracování odeslání zprávy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    // Kontrola prázdných polí
    if (empty($name) || empty($message)) {
        $error = "Vyplňte jméno i zprávu!";
    }
    // Ochrana proti spamu (časový limit)
    elseif (time() - $last_post_time < $spam_delay) {
        $error = "Posíláte zprávy příliš rychle! Zkuste to za pár sekund.";
    }
    else {
        $_SESSION['last_post_time'] = time();

        // Filtr nevhodných slov
        $message = filter_bad_words($message, $bad_words);

        // Uložení zprávy
        $new_entry = date('d.m.Y H:i') . " | " . htmlspecialchars($name) . ": " . htmlspecialchars($message) . PHP_EOL;
        $messages = file_exists($shoutbox_file) ? file($shoutbox_file, FILE_IGNORE_NEW_LINES) : [];
        array_unshift($messages, $new_entry); // Přidání na začátek

        // Zachování maximálního počtu zpráv
        if (count($messages) > $max_messages) {
            array_pop($messages);
        }

        file_put_contents($shoutbox_file, implode(PHP_EOL, $messages)); // Uložení
    }
}

// Načtení zpráv
$messages = file_exists($shoutbox_file) ? file($shoutbox_file, FILE_IGNORE_NEW_LINES) : [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Návštěvní kniha</title>
</head>
<body>

<header>
    <h1>Návštěvní kniha</h1>
</header>

<div class="container">
    <div class="content">
        <h2>Zanechte vzkaz</h2>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form action="guestbook.php" method="post">
            <input type="text" name="name" placeholder="Vaše jméno" required>
            <textarea name="message" placeholder="Vaše zpráva" required></textarea>
            <button type="submit">Odeslat</button>
        </form>

        <h2>Poslední zprávy</h2>
        <div class="messages">
            <?php if (!empty($messages)) {
                foreach ($messages as $msg) {
                    echo "<p>$msg</p>";
                }
            } else {
                echo "<p>Zatím žádné zprávy.</p>";
            } ?>
        </div>
    </div>
</div>

<footer>
   <?php include(path('includes', 'footer.php')); ?> 
</footer>

</body>
</html>