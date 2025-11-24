<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kyrian\TaskManagerApp\TaskManager;
use Kyrian\TaskManagerApp\Tasks\ProcessPizzaTask;
use Kyrian\TaskManagerApp\Tasks\ProcessSaladTask;
use Kyrian\TaskManagerApp\Tasks\ProcessPastaTask;
use Kyrian\TaskManagerApp\Tasks\CleanupTask;

$manager = new TaskManager();

$manager->add(new ProcessPizzaTask());
$manager->add(new ProcessSaladTask());
$manager->add(new ProcessPastaTask());
$manager->add(new CleanupTask());

$manager->addInterval(2.0, function() {
    echo "[Monitor] Checking kitchen status...\n";
});

echo "🍽️  Restaurant Kitchen Management System\n";
echo "=====================================\n\n";

$manager->run();