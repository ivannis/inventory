<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Event;

use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\Event\Handler\EventHandlerMap;
use Commanded\Core\Messaging\MessageNameTrait;

class EventHandler
{
    use MessageNameTrait;
    private EventHandlerMap $eventHandlerMap;

    public function __construct(EventHandlerMap $eventHandlerMap)
    {
        $this->eventHandlerMap = $eventHandlerMap;
    }
    
    public function dispatch(Envelope $envelope)
    {
        $handlers = $this->eventHandlerMap->getHandlers($this->getMessageName($envelope));
        foreach ($handlers as $handler) {
            call_user_func($handler, $envelope->message(), Envelope::clone($envelope));
        }
    }
}
