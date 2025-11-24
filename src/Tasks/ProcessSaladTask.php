<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\{wait, timeout};

class ProcessSaladTask extends Task {
    protected WashVegetablesTask $washTask;
    protected PrepareBowlTask $bowlTask;
    
    public function __construct() {
        $this->washTask = new WashVegetablesTask();
        $this->bowlTask = new PrepareBowlTask();
    }
    
    protected function execute(): mixed {
        echo "📋 [Order #102] Salad order received\n";
        
        timeout(2.5, function() {
            echo "⏰ [Order #102] Time to add dressing!\n";
        });

        $this->washTask->start();
        $this->bowlTask->start();

        wait(task: $this->washTask);
        wait(task: $this->bowlTask);

        echo "   [Order #102] Chopping ingredients...\n";
        wait(seconds: 1.5);
        echo "   [Order #102] ✓ Ingredients chopped\n";

        echo "   [Order #102] Mixing salad...\n";
        wait(seconds: 0.5);
        echo "   [Order #102] ✓ Salad mixed\n";
        
        echo "   [Order #102] Packaging...\n";
        wait(seconds: 0.3);
        echo "✅ [Order #102] Salad complete!\n\n";

        return "salad_complete";
    }
}