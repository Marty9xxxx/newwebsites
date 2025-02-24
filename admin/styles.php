<?php
// admin-styles.php

require_once dirname(__DIR__) . '/config.php';

// Načtení dat z JSONů
$users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$styles = json_decode(file_get_contents(getFilePath('data', 'styles.json')), true);

// Vložení hlavičky
include(getFilePath('includes', 'header.php'));

// Zpracování formuláře pro změnu stylu
if(isset($_POST['newStyle']) && in_array($_POST['newStyle'], $stylesConfig['availableStyles'])) {
    $stylesConfig['currentStyle'] = $_POST['newStyle'];
    file_put_contents(getFilePath('data', 'styles.json'), json_encode($stylesConfig, JSON_PRETTY_PRINT));
}

// Formulář pro výběr stylu
?>
<form method="POST">
    <select name="newStyle">
        <?php foreach($stylesConfig['availableStyles'] as $style): ?>
            <option value="<?php echo $style; ?>" 
                    <?php echo ($style === $stylesConfig['currentStyle']) ? 'selected' : ''; ?>>
                <?php echo ucfirst($style); ?> style
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Změnit styl</button>
</form>

<p>Aktuální styl: <?php echo ucfirst($stylesConfig['currentStyle']); ?></p>