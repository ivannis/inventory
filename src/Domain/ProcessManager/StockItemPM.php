<?php

declare(strict_types=1);

namespace Stock\Domain\ProcessManager;

use Commanded\Domain\Event\DomainEvent;
use Commanded\Domain\MessageBus;
use Stock\Domain\Command\Stock\CreateStockItem;
use Stock\Domain\Command\Stock\RemoveStockItem;
use Stock\Domain\Command\Stock\UpdateStockItem;
use Stock\Domain\Event\Stock\StockItemAdded;
use Stock\Domain\Event\Stock\StockItemRemoved;
use Stock\Domain\Event\Stock\StockItemUpdated;

class StockItemPM
{
    private MessageBus $bus;

    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(DomainEvent $event)
    {
        switch ($event->messageName()) {
            case StockItemAdded::EVENT_NAME:
                $this->onItemAdded($event);
                break;
            case StockItemUpdated::EVENT_NAME:
                $this->onItemUpdated($event);
                break;
            case StockItemRemoved::EVENT_NAME:
                $this->onItemRemoved($event);
                break;
        }
    }

    public function onItemAdded(StockItemAdded $event)
    {
        $this->bus->dispatch(new CreateStockItem(
            $event->id(),
            $event->stockId(),
            $event->quantity(),
            $event->unitPrice(),
            $event->createdAt(),
        ));
    }

    public function onItemUpdated(StockItemUpdated $event)
    {
        $this->bus->dispatch(new UpdateStockItem(
            $event->id(),
            $event->quantity(),
        ));
    }

    public function onItemRemoved(StockItemRemoved $event)
    {
        $this->bus->dispatch(new RemoveStockItem(
            $event->id(),
        ));
    }
}
