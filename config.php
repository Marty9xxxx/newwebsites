<?php
// Definice bezpečnostní konstanty
define('SECURITY', true);

// ====== KONFIGURACE CEST ======
/** 
 * Definice kořenového adresáře a webové cesty
 */
define('ROOT_DIR', dirname(__FILE__));
define('WEB_ROOT', '/ai-cviceni-web'); // Pro lokální vývoj

/**
 * Detekce URL webu
 */
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $domain . WEB_ROOT; // Přidáno WEB_ROOT

define('BASE_URL', $baseUrl);

// config.php
define('SYSTEM_PATH', str_replace('\\', '/', dirname(__FILE__)));

// ====== HELPER FUNKCE PRO PRÁCI S CESTAMI ======
/**
 * Získá absolutní cestu k souboru
 * @param string $dir Název složky (data, includes, ...)
 * @param string $file Název souboru
 * @return string Absolutní cesta k souboru
 */
function getFilePath($dir, $file) {
    return ROOT_DIR . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
}

/**
 * Získá URL cestu k souboru
 * @param string $path Relativní cesta k souboru
 * @return string URL cesta k souboru
 */
function getWebPath($path) {
    // Použijeme BASE_URL, které už obsahuje WEB_ROOT
    return BASE_URL . '/' . ltrim($path, '/');
}

// Automatické zálohování při změně dat
function saveJSON($data, $filename) {
    $filepath = getFilePath('data', $filename);
    
    // Vytvoření zálohy před uložením
    require_once dirname(__FILE__) . '/includes/Backup.php';
    $backup = new Backup();
    $backup->createBackup($filepath);
    
    // Uložení nových dat
    $result = file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT));
    
    // Vyčištění starých záloh
    $backup->cleanOldBackups();
    
    return $result !== false;
}