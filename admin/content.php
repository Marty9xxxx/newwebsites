<?php
require_once dirname(__DIR__) . '/config.php';

// Načtení obsahu z JSON
$content = json_decode(file_get_contents(getFilePath('data', 'content.json')), true);

// Zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'edit_content') {
        $content['homepage'] = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'last_edited' => date('Y-m-d H:i:s'),
            'edited_by' => $_SESSION['username']
        ];
        
        // Uložení změn
        file_put_contents(
            getFilePath('data', 'content.json'), 
            json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        header('Location: admin.php?section=content&success=1');
        exit;
    }
}
?>

<section class="content">
    <h2>Správa obsahu</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="message success">Obsah byl úspěšně uložen.</div>
    <?php endif; ?>

    <form method="post" class="content-form">
        <input type="hidden" name="action" value="edit_content">
        
        <div class="form-group">
            <label for="title">Nadpis stránky:</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="<?php echo htmlspecialchars($content['homepage']['title'] ?? ''); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="content">Obsah stránky:</label>
            <textarea id="content" 
                      name="content" 
                      rows="15" 
                      required><?php echo htmlspecialchars($content['homepage']['content'] ?? ''); ?></textarea>
        </div>

        <div class="form-info">
            <?php if (isset($content['homepage']['last_edited'])): ?>
                <p>
                    Naposledy upraveno: 
                    <?php echo date('d.m.Y H:i', strtotime($content['homepage']['last_edited'])); ?>
                    (<?php echo htmlspecialchars($content['homepage']['edited_by']); ?>)
                </p>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit">Uložit změny</button>
        </div>
    </form>
</section>

<style>
.content-form {
    max-width: 800px;
    margin: 20px 0;
}

.content-form .form-group {
    margin-bottom: 15px;
}

.content-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.content-form input[type="text"],
.content-form textarea {
    width: 100%;
    padding: 8px;
}

.content-form textarea {
    min-height: 200px;
}

.content-form .form-info {
    margin-top: 20px;
    font-size: 0.9em;
    color: #666;
}

.content-form .form-actions {
    margin-top: 20px;
}
</style>