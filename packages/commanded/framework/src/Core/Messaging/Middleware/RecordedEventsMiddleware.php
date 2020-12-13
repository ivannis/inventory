<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Middleware;

use Commanded\Core\Messaging\Envelope;
use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\Core\Messaging\EventBus;

class RecordedEventsMiddleware implements MessageBusMiddleware
{
    private EventRecorder $eventRecorder;
    private EventBus $eventBus;

    public function __construct(EventRecorder $eventRecorder, EventBus $eventBus)
    {
        $this->eventRecorder = $eventRecorder;
        $this->eventBus = $eventBus;
    }

    public function handle(Envelope $envelope, callable $next)
    {
        try {
            $next($envelope);
        } catch (\Throwable $exception) {
            $this->eventRecorder->clearRecordedEvents();

            throw $exception;
        }

        $recordedEvents = $this->eventRecorder->recordedEvents();
        $this->eventRecorder->clearRecordedEvents();

        foreach ($recordedEvents->getIterator() as $key => $recordedEvent) {
            $this->eventBus->dispatch($recordedEvent);
        }
    }
}
