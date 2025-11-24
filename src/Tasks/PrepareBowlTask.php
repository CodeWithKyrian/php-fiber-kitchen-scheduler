<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class PrepareBowlTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #102] Preparing bowl and utensils...\n";
        wait(seconds: 0.8);
        echo "   [Order #102] ✓ Bowl ready\n";
        return "bowl";
    }
}