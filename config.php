<?php
// config.php - umístěný v root adresáři webu
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));

function path($type, $file) {
    $base_path = dirname(__FILE__);
    switch ($type) {
        case 'data':
            return $base_path . '/data/' . $file;
        case 'styles':
            return $base_path . '/styles/' . $file;
        case 'includes':
            return $base_path . '/includes/' . $file;
        case 'admin':
            return $base_path . '/admin/' . $file;
        default:
            return $base_path . '/' . $file;
    }
}
?>
