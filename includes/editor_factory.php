<?php
class EditorFactory {
    public static function getEditor($fieldId, $content = '') {
        // Načteme nastavení uživatele
        $users = json_decode(file_get_contents(getFilePath('data', 'users.json')), true)['users'];
        $username = $_SESSION['username'] ?? '';
        $user = array_filter($users, fn($u) => $u['username'] === $username);
        $editorType = $user[0]['settings']['editor'] ?? 'simple';
        
        // Vytvoříme požadovaný editor
        if ($editorType === 'tinymce') {
            return new TinyMCEEditor($fieldId, $content);
        }
        
        return new SimpleEditor($fieldId, $content);
    }
}