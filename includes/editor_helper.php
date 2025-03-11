<?php
// Načtení obou editorů
require_once dirname(__DIR__) . '/includes/simple_editor.php';
require_once dirname(__DIR__) . '/includes/TinyMCEEditor.php';

function getUserPreferredEditor($fieldId, $content = '') {
    // Načtení uživatelských dat
    $userData = json_decode(file_get_contents(getFilePath('data', 'users.json')), true);
    
    // Debug výpis struktury dat
    debugLog('Načtená uživatelská data:', 'DEBUG');
    debugLog($userData, 'DEBUG');
    
    // Kontrola, zda máme správnou strukturu dat
    if (!is_array($userData)) {
        debugLog('Chyba: userData není pole', 'ERROR');
        return new SimpleEditor($fieldId, $content); // výchozí editor
    }
    
    // Najdeme aktuálního uživatele
    $currentUser = null;
    foreach ($userData as $user) {
        if ($user['username'] === $_SESSION['username']) {
            $currentUser = $user;
            break;
        }
    }
    
    // Debug výpis nalezeného uživatele
    debugLog('Nalezený uživatel:', 'DEBUG');
    debugLog($currentUser, 'DEBUG');
    
    // Výchozí editor je simple
    $editorType = $currentUser['settings']['editor'] ?? 'simple';
    
    // Vytvoření instance správného editoru
    if ($editorType === 'tinymce') {
        return new TinyMCEEditor($fieldId, $content);
    }
    
    return new SimpleEditor($fieldId, $content);
}