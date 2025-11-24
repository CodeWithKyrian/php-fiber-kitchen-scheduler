<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class BakePizzaTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #101] Baking pizza...\n";
        wait(seconds: 2.5);
        echo "   [Order #101] ✓ Pizza baked\n";
        return "pizza_baked";
    }
}