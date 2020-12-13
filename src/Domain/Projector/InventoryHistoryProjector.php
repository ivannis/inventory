<?php

declare(strict_types=1);

namespace Stock\Domain\Projector;

use Commanded\Domain\Event\DomainEvent;
use Stock\Domain\Event\Stock\StockItemAdded;
use Stock\Domain\Event\Stock\StockQuantityApplied;
use Stock\Domain\InventoryHistoryId;
use Stock\Domain\Repository\InventoryHistoryQueryRepository;

class InventoryHistoryProjector
{
    private InventoryHistoryQueryRepository $repository;

    public function __construct(InventoryHistoryQueryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DomainEvent $event)
    {
        switch ($event->messageName()) {
            case StockItemAdded::EVENT_NAME:
                $this->onPurchase($event);
                break;
            case StockQuantityApplied::EVENT_NAME:
                $this->onApply($event);
                break;
        }
    }

    public function onPurchase(StockItemAdded $event)
    {
        $state = array_merge([
            'type' => 'purchased'
        ], $event->toPayload());

        $this->repository->create(InventoryHistoryId::next(), $state);
    }

    public function onApply(StockQuantityApplied $event)
    {
        $state = array_merge($event->toPayload(), [
            'type' => 'application',
            'quantity' => -$event->quantity(),
        ]);

        $this->repository->create(InventoryHistoryId::next(), $state);
    }
}
