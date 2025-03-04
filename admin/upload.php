<?php
// ====== INICIALIZACE ======
// Načtení konfiguračního souboru s pomocnými funkcemi
require_once dirname(__DIR__) . '/config.php';

// ====== KONTROLA PŘIHLÁŠENÍ ======
// Ověření, zda je uživatel přihlášený
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    // Nastavení typu odpovědi na JSON
    header('Content-Type: application/json');
    // Ukončení scriptu s chybovou zprávou
    die(json_encode(['error' => 'Neautorizovaný přístup']));
}

// ====== ZPRACOVÁNÍ NAHRÁVANÉHO SOUBORU ======
// Kontrola, zda byl odeslán nějaký soubor
if (isset($_FILES['file'])) {
    // Uložení informací o souboru do proměnné pro lepší práci
    $file = $_FILES['file'];
    // Vytvoření unikátního názvu souboru (časová značka + očištěný název)
    $filename = time() . '_' . sanitize_filename($file['name']);
    // Nastavení cílové složky pro nahrávání
    $upload_dir = dirname(__DIR__) . '/uploads/images/';
    
    // ====== KONTROLA SLOŽKY ======
    // Vytvoření složky pro uploady, pokud neexistuje
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // ====== KONTROLA TYPU SOUBORU ======
    // Definice povolených typů souborů
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    // Kontrola, zda je typ nahrávaného souboru povolený
    if (!in_array($file['type'], $allowed)) {
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Nepovolený typ souboru']));
    }
    
    // ====== PŘESUN SOUBORU ======
    // Pokus o přesunutí nahraného souboru do cílové složky
    if (move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
        // Získání URL cesty k souboru pro použití ve webu
        $location = getWebPath('uploads/images/' . $filename);
        header('Content-Type: application/json');
        // Úspěšná odpověď kompatibilní s oběma editory
        echo json_encode([
            'location' => $location,    // Pro TinyMCE editor
            'url' => $location,         // Pro Simple editor
            'success' => true           // Příznak úspěchu
        ]);
    } else {
        // Chyba při přesunu souboru
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Chyba při nahrávání souboru']));
    }
} else {
    // Žádný soubor nebyl odeslán
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Žádný soubor nebyl nahrán']));
}

// ====== POMOCNÉ FUNKCE ======
/**
 * Očištění názvu souboru od problematických znaků
 * @param string $filename Původní název souboru
 * @return string Očištěný název souboru
 */
function sanitize_filename($filename) {
    // Odstranění diakritiky z názvu souboru
    $filename = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
    // Ponechání pouze písmen, čísel, pomlček a teček
    return preg_replace('/[^a-zA-Z0-9\-\.]/', '', $filename);
}