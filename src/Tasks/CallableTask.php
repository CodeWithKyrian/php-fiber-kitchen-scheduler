<?php

namespace Kyrian\TaskManagerApp\Tasks;

class CallableTask extends Task {
    private $callback;
    
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    
    protected function execute(): mixed {
        return call_user_func($this->callback);
    }
}
