<?php

declare(strict_types=1);

namespace Commanded\Domain\Aggregate;

use Bonami\Collection\LazyList;
use Commanded\Core\ValueObject\Identity\Id;
use Commanded\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    use EventSourcedTrait, SnapshotTrait;

    /** @var LazyList|DomainEvent[] */
    private LazyList $recordedEvents;

    private function __construct(string $id, int $version = 0, array $state = [])
    {
        $this->id = $id;
        $this->version = $version;
        $this->state = $state;

        $this->recordedEvents = LazyList::fromEmpty();
    }

    abstract public function id(): Id;

    public function recordedEvents(): LazyList
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents()
    {
        $this->recordedEvents = LazyList::fromEmpty();
    }

    protected function recordThat(DomainEvent $event)
    {
        $this->recordedEvents = $this->recordedEvents->add($event);
        $this->apply($event);
    }
}
