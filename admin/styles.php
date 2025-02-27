<?php
// Odstranit session_start() - session je již spuštěna v admin.php
require_once dirname(__DIR__) . '/config.php';

// Načtení konfigurace stylů
$stylesFile = getFilePath('data', 'styles.json');
$stylesConfig = json_decode(file_get_contents($stylesFile), true);

// Zpracování změny stylu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['style'])) {
    $newStyle = $_POST['style'];
    
    // Kontrola, zda je vybraný styl platný
    if (in_array($newStyle, $stylesConfig['availableStyles'])) {
        $stylesConfig['currentStyle'] = $newStyle;
        
        // Uložení změny do JSON souboru
        if (file_put_contents($stylesFile, json_encode($stylesConfig, JSON_PRETTY_PRINT))) {
            header('Location: admin.php?section=styles&success=1');
            exit;
        }
    }
}

// Načtení dostupných stylů
$availableStyles = $stylesConfig['availableStyles'] ?? [];
$currentStyle = $stylesConfig['currentStyle'] ?? 'default';
?>

<main>
    <section class="content">
        <h2>Správa vzhledu</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="message success">Styl byl úspěšně změněn.</div>
        <?php endif; ?>

        <p>Aktuální styl: <strong><?php echo htmlspecialchars(ucfirst($currentStyle)); ?></strong></p>

        <form method="post">
            <div>
                <label for="style">Vyberte styl:</label>
                <select name="style" id="style">
                    <?php foreach ($availableStyles as $style): ?>
                        <option value="<?php echo htmlspecialchars($style); ?>"
                                <?php echo ($style === $currentStyle) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(ucfirst($style)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Změnit styl</button>
        </form>
    </section>
</main>