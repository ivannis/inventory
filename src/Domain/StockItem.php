<?php

declare(strict_types=1);

namespace Stock\Domain;

use Commanded\Domain\Aggregate\AggregateRoot;
use Stock\Domain\Command\Stock\CreateStockItem;
use Stock\Domain\Command\Stock\UpdateStockItem;

class StockItem extends AggregateRoot
{
    public function id(): StockItemId
    {
        return StockItemId::fromNative($this->id);
    }

    public function create(CreateStockItem $command)
    {
        $this->state = \array_merge($this->state, $command->toPayload());
    }

    public function update(UpdateStockItem $command)
    {
        $this->state = \array_merge($this->state, $command->toPayload());
    }

    public function quantity(): int
    {
        return $this->state['quantity'];
    }

    public function hasStockSufficient(int $quantity): bool
    {
        return $this->state['quantity'] >= $quantity;
    }

    public function calculatePrice(int $quantity = null): float
    {
        return $this->state['unitPrice'] * ($quantity ?? $this->quantity());
    }

    public function onHand(): int
    {
        return $this->state['quantity'];
    }

    public function isOutOfStock(): bool
    {
        return $this->state['quantity'] === 0;
    }

    public function decrementStock(int $quantity)
    {
        $this->state['quantity'] -= $quantity;
    }
}
