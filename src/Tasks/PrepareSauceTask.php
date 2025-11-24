<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class PrepareSauceTask extends Task {
    protected function execute(): mixed {
        echo "   [Order #103] Preparing sauce...\n";
        wait(seconds: 1.5);
        echo "   [Order #103] ✓ Sauce ready\n";
        return "sauce_ready";
    }
}