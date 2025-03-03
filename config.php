<?php
// Definice bezpečnostní konstanty
define('SECURITY', true);

// config.php
define('SYSTEM_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('WEB_ROOT', '/ai-cviceni-web'); 

// Pro soubory
function getFilePath($type, $path = '') {
    $path = ltrim($path, '/');
    $fullPath = SYSTEM_PATH . '/' . $type;
    
    if (!is_dir($fullPath)) {
        throw new \Exception("Složka '$type' neexistuje!");
    }
    
    return $fullPath . '/' . $path;
}

// Pro odkazy
function getWebPath($path = '') {
    return WEB_ROOT . '/' . ltrim($path, '/');
}