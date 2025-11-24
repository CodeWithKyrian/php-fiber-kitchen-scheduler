<?php

namespace Kyrian\TaskManagerApp;

use Kyrian\TaskManagerApp\Tasks\CallableTask;
use Kyrian\TaskManagerApp\Tasks\Task;

class TaskManager
{
    private array $tasks = [];
    private array $timers = [];
    private array $timeouts = [];
    private array $intervals = [];
    private float $startTime;

    private static ?TaskManager $current = null;

    public function __construct()
    {
        $this->startTime = microtime(true);
        self::$current = $this;
    }

    public static function current(): self
    {
        return self::$current;
    }

    public function add(Task|callable $task): Task
    {
        if (!$task instanceof Task) {
            $task = new CallableTask($task);
        }

        foreach ($this->tasks as $item) {
            if ($item['task'] === $task) {
                return $task;
            }
        }

        $this->tasks[] = [
            'task' => $task,
            'id' => uniqid(),
            'waiting_for' => null,
        ];
        return $task;
    }

    public function addTimeout(float $seconds, callable $callback): void
    {
        $this->timeouts[] = [
            'fire_at' => $this->now() + $seconds,
            'callback' => $callback,
            'executed' => false,
        ];
    }

    public function addInterval(float $seconds, callable $callback): int
    {
        static $nextId = 1;
        $id = $nextId++;

        $this->intervals[$id] = [
            'seconds' => $seconds,
            'callback' => $callback,
            'next_run' => $this->now() + $seconds,
        ];

        return $id;
    }

    public function clearInterval(int $id): void
    {
        unset($this->intervals[$id]);
    }

    public function run(): void
    {
        // Start all tasks added before run()
        foreach ($this->tasks as $item) {
            $item['task']->start();
        }

        // Main loop - runs until all TASKS complete
        while (!empty($this->getActiveTasks())) {
            $this->processTasks();
            $this->processTimers();
            $this->processTimeouts();
            $this->processIntervals();
            usleep(10000);  // 10ms tick
        }

        echo "\n✓ All tasks completed!\n";
    }

    private function processTimers(): void
    {
        $now = $this->now();

        foreach ($this->timers as $key => $timer) {
            if ($now >= $timer['resume_at'] && !$timer['executed']) {
                $this->timers[$key]['executed'] = true;
                $fiber = $timer['fiber'];

                if ($fiber->isSuspended()) {
                    $result = $fiber->resume();
                    $this->handleSuspension($result, $timer['task_key']);
                }

                unset($this->timers[$key]);
            }
        }
    }

    private function processTimeouts(): void
    {
        $now = $this->now();

        foreach ($this->timeouts as $key => $timeout) {
            if ($now >= $timeout['fire_at'] && !$timeout['executed']) {
                $this->timeouts[$key]['executed'] = true;
                $callback = $timeout['callback'];
                $callback();
                unset($this->timeouts[$key]);
            }
        }
    }

    private function processIntervals(): void
    {
        $now = $this->now();

        foreach ($this->intervals as $id => $interval) {
            if ($now >= $interval['next_run']) {
                $this->intervals[$id]['next_run'] = $now + $interval['seconds'];
                $callback = $interval['callback'];
                $callback();
            }
        }
    }

    private function processTasks(): void
    {
        foreach ($this->tasks as $key => $item) {
            $fiber = $item['task']->getFiber();

            // Start fibers that haven't been started yet
            if (!$fiber->isStarted()) {
                $result = $fiber->start();
                $this->handleSuspension($result, $key);
                continue;
            }

            // Mark terminated tasks as complete
            if ($fiber->isTerminated()) {
                if (!$item['task']->isComplete()) {
                    $this->tasks[$key]['task']->markComplete($fiber->getReturn());
                }
                continue;
            }

            // Resume tasks waiting for other tasks
            if ($fiber->isSuspended() && $item['waiting_for'] !== null) {
                $waitedTask = $item['waiting_for'];
                if ($waitedTask->isComplete()) {
                    $this->tasks[$key]['waiting_for'] = null;
                    $result = $fiber->resume($waitedTask->getResult());
                    $this->handleSuspension($result, $key);
                }
            }
        }
    }

    private function handleSuspension($result, int $taskKey): void
    {
        $taskItem = $this->tasks[$taskKey];
        $fiber = $taskItem['task']->getFiber();

        if ($fiber->isTerminated()) {
            $this->tasks[$taskKey]['task']->markComplete($fiber->getReturn());
            return;
        }

        if (is_array($result) && isset($result['type']) && $result['type'] === 'wait') {
            if (isset($result['seconds'])) {
                // Wait for time duration
                $this->timers[] = [
                    'resume_at' => $this->now() + $result['seconds'],
                    'fiber' => $fiber,
                    'task_key' => $taskKey,
                    'executed' => false,
                ];
            } elseif (isset($result['task'])) {
                // Wait for another task
                $this->tasks[$taskKey]['waiting_for'] = $result['task'];
            }
        }
    }

    private function getActiveTasks(): array
    {
        return array_filter($this->tasks, function ($item) {
            return !$item['task']->getFiber()->isTerminated();
        });
    }

    private function now(): float
    {
        return microtime(true) - $this->startTime;
    }
}
