<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Event\Recorder;

use Bonami\Collection\LazyList;
use Commanded\Core\Messaging\Event\Event;

class EventRecorder
{
    private LazyList $recordedEvents;

    public function __construct()
    {
        $this->recordedEvents = LazyList::fromEmpty();
    }

    /**
     * @return LazyList|Event[]
     */
    public function recordedEvents(): LazyList
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents()
    {
        $this->recordedEvents = LazyList::fromEmpty();
    }

    public function recordEvents(Event ...$events)
    {
        $this->recordedEvents = $this->recordedEvents->add(...$events);
    }
}
