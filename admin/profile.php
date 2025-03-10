<div class="form-group">
    <label for="editor">Preferovaný editor:</label>
    <select name="editor" id="editor">
        <option value="simple" <?php echo ($user['settings']['editor'] ?? 'simple') === 'simple' ? 'selected' : ''; ?>>
            Simple Editor (BB kódy)
        </option>
        <option value="tinymce" <?php echo ($user['settings']['editor'] ?? 'simple') === 'tinymce' ? 'selected' : ''; ?>>
            TinyMCE (Pokročilý)
        </option>
    </select>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ...existing code...
    
    // Uložení nastavení editoru
    $users[$userIndex]['settings']['editor'] = $_POST['editor'];
    
    // Uložení změn
    if (file_put_contents(
        getFilePath('data', 'users.json'),
        json_encode(['users' => $users], JSON_PRETTY_PRINT)
    )) {
        header('Location: admin.php?section=profile&success=1');
        exit;
    }
}
?>