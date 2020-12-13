<?php

declare(strict_types=1);

namespace Commanded\Domain\Aggregate;

use Bonami\Collection\LazyList;
use Commanded\Core\Utils\ClassUtils;
use Commanded\Core\ValueObject\Identity\Id;
use Commanded\Domain\Event\DomainEvent;

/**
 * @method static static fromId(Id $id)
 */
trait EventSourcedTrait
{
    protected string $id;
    protected int $version = 0;

    private function apply(DomainEvent $event)
    {
        $method = 'when' . ClassUtils::short($event);
        if (method_exists($this, $method)) {
            $this->$method($event);
            ++$this->version;
        }
    }

    public function version(): int
    {
        return $this->version;
    }

    public function replay(LazyList $events)
    {
        foreach ($events as $event) {
            $this->apply($event);
        }
    }

    public static function reconstituteFromEvents(string $id, LazyList $events): self
    {
        $aggregate = new static($id);
        $aggregate->replay($events);

        return $aggregate;
    }
}
