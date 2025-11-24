# PHP Fiber Kitchen Scheduler

This repo powers the practical example from my article, [PHP Fibers: The Missing Piece for Elegant Cooperative Multitasking](https://codewithkyrian.com/p/php-fibers-the-missing-piece-for-elegant-cooperative-multitasking). It demonstrates how a single PHP process can juggle multiple “kitchen orders” concurrently by coordinating lightweight Fibers through a cooperative TaskManager loop.

## Requirements

- PHP 8.2+ (Fibers landed in 8.1, but the example uses typed properties and features that are easier on 8.2+)
- Composer

## Getting Started

```bash
git clone https://github.com/CodeWithKyrian/php-fiber-kitchen-scheduler.git
cd php-fiber-kitchen-scheduler
composer install
```

## Running the Simulation

```bash
php kitchen.php
```

You’ll get a log that looks like a busy kitchen: pizza, salad, and pasta orders run in parallel, periodic monitors print status updates, and a cleanup task waits in the wings. Behind the scenes each “order” is a fiber that yields via helper functions like `wait(seconds: 2.0)` or `wait(task: $otherTask)`; the `TaskManager` decides which fiber should run next so everything keeps moving without threads or async extensions.

## Tinkering Ideas

- **Add new orders:** Create a class under `src/Tasks`, extend `Task`, and register it in `kitchen.php`.
- **Play with intervals/timeouts:** The helper functions in `src/helpers.php` show how to schedule background callbacks without blocking.
- **Trace fiber hand-offs:** Sprinkle additional `echo` statements or `var_dump()` calls inside `TaskManager::handleSuspension()` to see exactly when suspensions/resumptions occur.