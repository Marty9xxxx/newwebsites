<?php
// config.php - umístěný v root adresáři webu
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));

function path($type, $path = '') {
    switch($type) {
        case 'admin':  // Pro admin soubory
            return BASE_PATH . '/admin/' . ltrim($path, '/');
        case 'includes': // Pro soubory v includes
            return BASE_PATH . '/includes/' . ltrim($path, '/');
        case 'styles': // Pro soubory se styly
            return BASE_PATH . '/styles/' . ltrim($path, '/');
        case 'errors': // Pro soubory s chybami
            return BASE_PATH . '/errors/' . ltrim($path, '/');
        case 'core': // Pro soubory kontrol a sestavování opakujících se částí
            return BASE_PATH . '/core/' . ltrim($path, '/');
        case 'data':   // Pro JSON data
            return BASE_PATH . '/data/' . ltrim($path, '/');
        case 'web':    // Pro URL adresy
            return '/' . ltrim($path, '/');
    }
}
