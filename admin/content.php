<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/simple_editor.php';
require_once dirname(__DIR__) . '/includes/TinyMCEEditor.php';
require_once dirname(__DIR__) . '/includes/editor_helper.php';
require_once dirname(__DIR__) . '/includes/debug_helper.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
// Odstraníme session_start(), protože už je volán v admin.php
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ' . getWebPath('admin/login.php'));
    exit;
}

// Načtení aktuálního uživatele pro zjištění preferovaného editoru
$userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
$currentUser = null;
foreach ($userData as $user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUser = $user;
        break;
    }
}

// Debug výpis
error_log('Current user settings: ' . print_r($currentUser['settings'] ?? [], true));

// Debug výpisy
debugLog('Načítání editoru pro uživatele: ' . $_SESSION['username']);
debugLog('Uživatelská nastavení:', 'DEBUG');
debugLog($currentUser['settings'] ?? [], 'DEBUG');

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
            'title' => $_POST['title'],
            // Uložíme přímo BB kódy, převedou se až při zobrazení
            'content' => $_POST['content'],
            'last_edited' => date('Y-m-d H:i:s'),
            'edited_by' => $_SESSION['username']
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
            <?php
            $rawContent = $content['homepage']['content'] ?? '';
            $editor = getUserPreferredEditor('content', $rawContent);
            
            // Debug výpis
            error_log('Selected editor type: ' . get_class($editor));
            debugLog('Vybraný typ editoru: ' . get_class($editor));
            
            $editor->render();
            ?>
        </div>

        <!-- Pro náhled přidáme: -->
        <div class="content-preview">
            <h3>Náhled:</h3>
            <div class="preview-content">
                <?php echo SimpleEditor::parseContent($rawContent); ?>
            </div>
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

/* Styly pro Simple Editor */
.content-form .simple-editor {
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
}

.content-form .editor-toolbar {
    padding: 5px;
    background:rgb(207, 206, 206);
    border-bottom: 1px solid #ccc;
}

.content-form .editor-toolbar button {
    padding: 5px 10px;
    margin-right: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    background:rgb(225, 220, 220);
    cursor: pointer;
}

.content-form .editor-toolbar button:hover {
    background: #eee;
}

.content-form .simple-editor textarea {
    width: 100%;
    min-height: 300px;
    padding: 10px;
    border: none;
    resize: vertical;
}

/* Styl pro náhled obsahu */
.content-preview {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
}

.content-preview h3 {
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.preview-content {
    line-height: 1.6;
}

.preview-content img {
    max-width: 100%;
    height: auto;
}

.preview-content a {
    color: #0066cc;
    text-decoration: none;
}

.preview-content a:hover {
    text-decoration: underline;
}
</style>

<!-- Přidáme před uzavírací tag </head> -->
<?php if (strpos($_SERVER['PHP_SELF'], 'admin') !== false): ?>
    <!-- TinyMCE skript -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
<?php endif; ?>

<!-- Přidáme před uzavírací tag </body> -->
<script src="<?php echo getWebPath('js/simple_editor.js'); ?>"></script>