<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class PrepareIngredientsTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #101] Preparing ingredients...\n";
        wait(seconds: 1.0);
        echo "   [Order #101] ✓ Ingredients ready\n";
        return "ingredients_ready";
    }
}