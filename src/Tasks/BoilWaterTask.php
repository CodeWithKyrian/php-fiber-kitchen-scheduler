<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class BoilWaterTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #103] Boiling water...\n";
        wait(seconds: 2.0);
        echo "   [Order #103] ✓ Water boiling\n";
        return "water_ready";
    }
}