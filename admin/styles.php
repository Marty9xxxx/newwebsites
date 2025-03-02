<?php
// ====== INICIALIZACE ======
// Odstranění session_start(), protože session je již spuštěna v admin.php
require_once dirname(__DIR__) . '/config.php';

// ====== NAČTENÍ KONFIGURACE ======
// Získání cesty k souboru s nastaveními stylů
$stylesFile = getFilePath('data', 'styles.json');
// Načtení JSON konfigurace a převod na PHP pole
$stylesConfig = json_decode(file_get_contents($stylesFile), true);

// ====== ZPRACOVÁNÍ ZMĚNY STYLU ======
// Kontrola, zda byl odeslán formulář metodou POST a obsahuje vybraný styl
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['style'])) {
    $newStyle = $_POST['style'];
    
    // Bezpečnostní kontrola - ověření, zda je vybraný styl v seznamu povolených
    if (in_array($newStyle, $stylesConfig['availableStyles'])) {
        // Aktualizace aktivního stylu
        $stylesConfig['currentStyle'] = $newStyle;
        
        // Uložení změny do JSON souboru s formátováním pro čitelnost
        if (file_put_contents($stylesFile, json_encode($stylesConfig, JSON_PRETTY_PRINT))) {
            // Přesměrování zpět s informací o úspěchu
            header('Location: admin.php?section=styles&success=1');
            exit;
        }
    }
}

// ====== PŘÍPRAVA DAT PRO ZOBRAZENÍ ======
// Načtení seznamu dostupných stylů a aktuálně aktivního stylu
// Operátor ?? poskytuje výchozí hodnotu, pokud klíč neexistuje
$availableStyles = $stylesConfig['availableStyles'] ?? [];
$currentStyle = $stylesConfig['currentStyle'] ?? 'default';
?>

<!-- ====== HTML STRUKTURA ====== -->
<main>
    <section class="content">
        <h2>Správa vzhledu</h2>
        
        <!-- Zobrazení zprávy o úspěšné změně -->
        <?php if (isset($_GET['success'])): ?>
            <div class="message success">Styl byl úspěšně změněn.</div>
        <?php endif; ?>

        <!-- Informace o aktuálním stylu -->
        <p>Aktuální styl: <strong><?php echo htmlspecialchars(ucfirst($currentStyle)); ?></strong></p>

        <!-- Formulář pro změnu stylu -->
        <form method="post">
            <div>
                <label for="style">Vyberte styl:</label>
                <!-- Select box s dostupnými styly -->
                <select name="style" id="style">
                    <?php foreach ($availableStyles as $style): ?>
                        <!-- Výpis každého dostupného stylu jako možnosti -->
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