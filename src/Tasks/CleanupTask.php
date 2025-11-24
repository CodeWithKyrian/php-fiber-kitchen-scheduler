<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\wait;

class CleanupTask extends Task {
    protected function execute(): mixed {
        echo "📋 [Cleanup] Scheduled for 6 seconds\n";
        wait(seconds: 6.0);
        echo "🧹 [Cleanup] Starting kitchen cleanup...\n";
        wait(seconds: 0.5);
        echo "🧹 [Cleanup] Wiping counters...\n";
        wait(seconds: 0.5);
        echo "🧹 [Cleanup] Done!\n\n";

        return "cleanup_complete";
    }
}