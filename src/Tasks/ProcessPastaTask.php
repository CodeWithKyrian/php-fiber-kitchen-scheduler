<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\{wait, timeout};

class ProcessPastaTask extends Task {
    protected BoilWaterTask $boilTask;
    protected PrepareSauceTask $sauceTask;
    
    public function __construct() {
        $this->boilTask = new BoilWaterTask();
        $this->sauceTask = new PrepareSauceTask();
    }

    protected function execute(): mixed {
        echo "📋 [Order #103] Pasta order received\n";

        timeout(1.0, function() {
            echo "⏰ [Order #103] Water should be boiling soon...\n";
        });

        timeout(2.5, function() {
            echo "⏰ [Order #103] Remember to stir the pasta!\n";
        });
        
        timeout(4.0, function() {
            echo "⏰ [Order #103] Check if pasta is al dente!\n";
        });

        $this->boilTask->start();
        $this->sauceTask->start();

        wait(task: $this->boilTask);

        echo "   [Order #103] Cooking pasta...\n";
        wait(seconds: 3.0);
        echo "   [Order #103] ✓ Pasta cooked\n";

        wait(task: $this->sauceTask);
        
        echo "   [Order #103] Combining pasta and sauce...\n";
        wait(seconds: 0.5);
        echo "   [Order #103] ✓ Combined\n";

        echo "   [Order #103] Placing...\n";
        wait(seconds: 0.5);
        echo "✅ [Order #103] Pasta complete!\n\n";

        return "pasta_complete";
    }
}