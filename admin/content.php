<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== NAČTENÍ DAT ======
// Načtení obsahu z JSON souboru a převod do PHP pole
// Použití true jako druhého parametru pro získání asociativního pole
$content = json_decode(file_get_contents(getFilePath('data', 'content.json')), true);

// ====== ZPRACOVÁNÍ FORMULÁŘE ======
// Kontrola, zda byl odeslán POST požadavek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kontrola, zda se jedná o akci úpravy obsahu
    if (isset($_POST['action']) && $_POST['action'] === 'edit_content') {
        // Aktualizace dat v poli content
        $content['homepage'] = [
            'title' => $_POST['title'],          // Nový nadpis
            'content' => $_POST['content'],      // Nový obsah
            'last_edited' => date('Y-m-d H:i:s'),// Aktuální datum a čas
            'edited_by' => $_SESSION['username'] // Přihlášený uživatel
        ];
        
        // Uložení změn do JSON souboru
        // JSON_PRETTY_PRINT - formátovaný výstup
        // JSON_UNESCAPED_UNICODE - správné zobrazení češtiny
        file_put_contents(
            getFilePath('data', 'content.json'), 
            json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        // Přesměrování zpět s informací o úspěchu
        header('Location: admin.php?section=content&success=1');
        exit;
    }
}
?>

<!-- ====== HTML ČÁST ====== -->
<section class="content">
    <h2>Správa obsahu</h2>
    
    <!-- Zobrazení zprávy o úspěšném uložení -->
    <?php if (isset($_GET['success'])): ?>
        <div class="message success">Obsah byl úspěšně uložen.</div>
    <?php endif; ?>

    <!-- Formulář pro úpravu obsahu -->
    <form method="post" class="content-form">
        <!-- Skryté pole pro identifikaci akce -->
        <input type="hidden" name="action" value="edit_content">
        
        <!-- Pole pro nadpis -->
        <div class="form-group">
            <label for="title">Nadpis stránky:</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="<?php echo htmlspecialchars($content['homepage']['title'] ?? ''); ?>" 
                   required>
        </div>

        <!-- Pole pro obsah -->
        <div class="form-group">
            <label for="content">Obsah stránky:</label>
            <textarea id="content" 
                      name="content" 
                      rows="15" 
                      required><?php echo htmlspecialchars($content['homepage']['content'] ?? ''); ?></textarea>
        </div>

        <!-- Informace o poslední úpravě -->
        <div class="form-info">
            <?php if (isset($content['homepage']['last_edited'])): ?>
                <p>
                    Naposledy upraveno: 
                    <?php echo date('d.m.Y H:i', strtotime($content['homepage']['last_edited'])); ?>
                    (<?php echo htmlspecialchars($content['homepage']['edited_by']); ?>)
                </p>
            <?php endif; ?>
        </div>

        <!-- Tlačítka formuláře -->
        <div class="form-actions">
            <button type="submit">Uložit změny</button>
        </div>
    </form>
</section>

<!-- ====== CSS STYLY ====== -->
<style>
/* Základní styl formuláře */
.content-form {
    max-width: 800px;
    margin: 20px 0;
}

/* Odsazení skupin formulářových prvků */
.content-form .form-group {
    margin-bottom: 15px;
}

/* Styl popisků formuláře */
.content-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Styl vstupních polí */
.content-form input[type="text"],
.content-form textarea {
    width: 100%;
    padding: 8px;
}

/* Minimální výška pro textarea */
.content-form textarea {
    min-height: 200px;
}

/* Styl informačního bloku */
.content-form .form-info {
    margin-top: 20px;
    font-size: 0.9em;
    color: #666;
}

/* Styl pro tlačítka */
.content-form .form-actions {
    margin-top: 20px;
}
</style>