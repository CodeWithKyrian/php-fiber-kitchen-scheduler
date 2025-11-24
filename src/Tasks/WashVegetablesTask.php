<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class WashVegetablesTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #102] Washing vegetables...\n";
        wait(seconds: 1.0);
        echo "   [Order #102] ✓ Vegetables washed\n";
        return "vegetables_washed";
    }
}