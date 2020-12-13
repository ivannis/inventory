<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Middleware;

use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\Event\Event;
use Commanded\Core\Messaging\Event\EventHandler;

class EventHandlerMiddleware implements MessageBusMiddleware
{
    private EventHandler $eventHandler;

    public function __construct(EventHandler $eventHandler)
    {
        $this->eventHandler = $eventHandler;
    }

    public function handle(Envelope $envelope, callable $next)
    {
        $event = $envelope->message();
        if (!$event instanceof Event) {
            throw new \Exception(
                sprintf(
                    'The message %s must be an instance of "%s"',
                    \get_class($event),
                    Event::class
                )
            );
        }

        $this->eventHandler->dispatch(Envelope::clone($envelope));

        $next($envelope);
    }
}
