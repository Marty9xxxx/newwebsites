<?php
class Backup {
    private $backupDir;
    private $retentionDays = 30;

    public function __construct() {
        // Vytvoření složky pro zálohy v kořenovém adresáři webu
        $this->backupDir = dirname(__DIR__) . '/backups/';
        if (!file_exists($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }
    
    // Jednoduchá záloha do ZIP souboru
    public function backup() {
        $zip = new ZipArchive();
        $zipName = $this->backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.zip';
        
        if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
            // Zálohování JSON souborů
            $this->addToZip($zip, dirname(__DIR__) . '/data/');
            $zip->close();
            return true;
        }
        return false;
    }

    private function addToZip($zip, $path) {
        $files = glob($path . '*.json');
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
    }
}