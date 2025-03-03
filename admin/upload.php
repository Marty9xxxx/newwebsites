<?php
require_once dirname(__DIR__) . '/config.php';

// Kontrola přihlášení
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die('Unauthorized');
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = time() . '_' . sanitize_filename($file['name']);
    $upload_dir = dirname(__DIR__) . '/uploads/images/';
    
    // Vytvoření složky, pokud neexistuje
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Kontrola typu souboru
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed)) {
        die('Invalid file type');
    }
    
    if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
        echo json_encode([
            'location' => getWebPath('uploads/images/' . $filename)
        ]);
    }
}

function sanitize_filename($filename) {
    // Odstranění diakritiky
    $filename = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
    // Pouze písmena, čísla a pomlčky
    return preg_replace('/[^a-zA-Z0-9\-\.]/', '', $filename);
}