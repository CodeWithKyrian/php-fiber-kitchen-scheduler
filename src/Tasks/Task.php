<?php

namespace Kyrian\TaskManagerApp\Tasks;

use Kyrian\TaskManagerApp\TaskManager;

class Task
{
    private \Fiber $fiber;
    private bool $started = false;
    private bool $complete = false;
    private mixed $result = null;

    protected function execute(): mixed
    {
        return null;
    }

    public function start(): void
    {
        if ($this->started) {
            return;
        }

        $this->started = true;

        $this->fiber = new \Fiber(fn() => $this->execute());

        $manager = TaskManager::current();

        if (!$manager) {
            throw new \RuntimeException('No TaskManager available');
        }

        $manager->add($this);
    }

    public function getFiber(): \Fiber
    {
        return $this->fiber;
    }

    public function isStarted(): bool
    {
        return $this->started;
    }

    public function isComplete(): bool
    {
        return $this->complete || ($this->fiber && $this->fiber->isTerminated());
    }

    public function markComplete(mixed $result): void
    {
        $this->complete = true;
        $this->result = $result;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }
}
