<?php

namespace Kyrian\TaskManagerApp\Tasks;

use function Kyrian\TaskManagerApp\{wait, timeout};

class ProcessPizzaTask extends Task {
    protected PrepareIngredientsTask $prepTask;
    protected BakePizzaTask $bakeTask;
    
    public function __construct() {
        $this->prepTask = new PrepareIngredientsTask();
        $this->bakeTask = new BakePizzaTask();
    }

    protected function execute(): mixed {
        echo "📋 [Order #101] Pizza order received\n";
        
        timeout(3.0, function() {
            echo "⏰ [Order #101] Reminder: Check pizza temperature!\n";
        });
        
        timeout(4.5, function() {
            echo "⏰ [Order #101] Don't forget extra cheese!\n";
        });
        
        $this->prepTask->start();
        wait(task: $this->prepTask);
        
        $this->bakeTask->start();
        wait(task: $this->bakeTask);
        
        echo "   [Order #101] Adding toppings...\n";
        wait(seconds: 0.5);
        echo "   [Order #101] ✓ Toppings added\n";
        
        echo "   [Order #101] Packaging...\n";
        wait(seconds: 0.5);
        echo "✅ [Order #101] Pizza complete!\n\n";

        return "pizza_complete";
    }
}