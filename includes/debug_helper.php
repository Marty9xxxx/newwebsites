<?php
function debugLog($message, $type = 'INFO') {
    $logDir = dirname(__DIR__) . '/logs';
    $logFile = $logDir . '/debug.log';
    
    // Vytvoření adresáře pro logy, pokud neexistuje
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    // Formát: [DATUM ČAS] [TYP] Zpráva
    $logMessage = sprintf(
        "[%s] [%s] %s\n",
        date('Y-m-d H:i:s'),
        strtoupper($type),
        is_array($message) || is_object($message) ? print_r($message, true) : $message
    );
    
    // Zápis do logu
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}