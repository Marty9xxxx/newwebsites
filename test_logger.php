<?php
require_once 'includes/Logger.php';

// Vytvoření instance loggeru
$logger = new Logger('test');

// Test zápisu
$logger->write('Test logovací zprávy');
$logger->write('Chybová zpráva', 'ERROR');
$logger->write('Varování', 'WARNING');

echo "Zkontroluj složku logs pro výsledky!";