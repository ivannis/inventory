<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Event\Handler;

use SplPriorityQueue;

class EventHandlerMap
{
    private array $container = [];

    public function __construct(array $eventMap)
    {
        foreach ($eventMap as $eventName => $eventHandlers) {
            foreach ($eventHandlers as list($eventHandler, $priority)) {
                $this->register($eventName, $eventHandler, $priority);
            }
        }
    }

    public function getHandlers(string $eventName): iterable
    {
        if (!isset($this->container[$eventName])) {
            return [];
        }

        $queue = new SplPriorityQueue();
        foreach ($this->container[$eventName] as ['handler' => $handler, 'priority' => $priority]) {
            $queue->insert($handler, $priority);
        }

        return $queue;
    }

    private function register(string $eventName, callable $eventHandler, int $priority = 0)
    {
        $this->container[$eventName][] = [
            'handler' => $eventHandler,
            'priority' => $priority,
        ];
    }
}
