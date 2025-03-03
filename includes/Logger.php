<?php
class Logger {
    private $logDir;
    private $logFile;

    public function __construct($type = 'general') {
        $this->logDir = dirname(__DIR__) . '/logs';
        
        // Vytvoření složky pro logy, pokud neexistuje
        if (!file_exists($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
        
        $this->logFile = sprintf(
            '%s/%s_%s.log',
            $this->logDir,
            $type,
            date('Y_m')
        );
    }

    public function write($message, $type = 'INFO') {
        $logEntry = sprintf(
            "[%s] [%s] %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($type),
            $message
        );
        
        return file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}