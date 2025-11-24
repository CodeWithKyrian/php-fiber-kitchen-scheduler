<?php

namespace Kyrian\TaskManagerApp;

use Kyrian\TaskManagerApp\Tasks\Task;
use Kyrian\TaskManagerApp\TaskManager;

function wait(?Task $task = null, ?float $seconds = null): mixed {
    if ($task !== null && $seconds !== null) {
        throw new \InvalidArgumentException('Cannot wait for both task and seconds');
    }
    
    if ($task === null && $seconds === null) {
        throw new \InvalidArgumentException('Must provide either task or seconds');
    }
    
    if ($task !== null) {
        if (!$task->isStarted()) {
            throw new \RuntimeException('Task must be started before waiting');
        }
        
        if ($task->isComplete()) {
            return $task->getResult();
        }
        
        return \Fiber::suspend(['type' => 'wait', 'task' => $task]);
    }
    
    if ($seconds !== null) {
        \Fiber::suspend(['type' => 'wait', 'seconds' => $seconds]);
    }

    return null;
}

function timeout(float $seconds, callable $callback): void {
    $manager = TaskManager::current();
 
    if (!$manager) {
        throw new \RuntimeException('No TaskManager available');
    }
 
    $manager->addTimeout($seconds, $callback);
}

function interval(float $seconds, callable $callback): int {
    $manager = TaskManager::current();
 
    if (!$manager) {
        throw new \RuntimeException('No TaskManager available');
    }
 
    return $manager->addInterval($seconds, $callback);
}