<?php

declare(strict_types=1);

namespace Stock\Domain;

use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Core\ValueObject\Money\Money;
use Commanded\Domain\Aggregate\AggregateRoot;
use Hyperf\Utils\Collection;
use Stock\Domain\Command\Stock\AddStockItem;
use Stock\Domain\Command\Stock\ApplyQuantityToStock;
use Stock\Domain\Command\Stock\CreateStock;
use Stock\Domain\Event\Stock\StockCreated;
use Stock\Domain\Event\Stock\StockItemAdded;
use Stock\Domain\Event\Stock\StockItemRemoved;
use Stock\Domain\Event\Stock\StockItemUpdated;
use Stock\Domain\Event\Stock\StockQuantityApplied;
use Stock\Domain\Event\Stock\StockUpdated;
use Stock\Domain\Exception\InsufficientProductQuantity;
use Stock\Domain\Exception\ProductOutOfStock;

class Stock extends AggregateRoot
{
    public function id(): StockId
    {
        return StockId::fromNative($this->id);
    }

    public function create(CreateStock $command)
    {
        $this->recordThat(new StockCreated(
            $this->id(),
            $command->productId(),
            $command->currency()
        ));
    }

    public function addStockItem(AddStockItem $command)
    {
        $quantity = $this->onHand() + $command->quantity();
        $valuation = $this->valuation() + $command->unitPrice()->multiplyBy($command->quantity())->amount();

        $this->recordThat(new StockUpdated(
            $this->id(),
            $quantity,
            $valuation
        ));

        $this->recordThat(new StockItemAdded(
            StockItemId::next(),
            $this->id(),
            $command->quantity(),
            $command->unitPrice(),
            $command->createdAt()
        ));
    }

    /**
     * @param Collection|StockItem[] $stockItems
     */
    public function applyQuantityToStock(ApplyQuantityToStock $command, Collection $stockItems)
    {
        $this->assertStock($command->quantity());

        $total = 0;
        $remaining = $command->quantity();

        foreach ($stockItems as $stockItem) {
            if ($stockItem->hasStockSufficient($remaining)) {
                $quantity = $stockItem->onHand() - $remaining;
                $total += $stockItem->calculatePrice($remaining);
                $stockItem->decrementStock($remaining);

                if ($stockItem->isOutOfStock()) {
                    $this->recordThat(new StockItemRemoved($stockItem->id(), $command->createdAt()));
                } else {
                    $this->recordThat(new StockItemUpdated($stockItem->id(), $quantity));
                }
                break;
            }

            // the item has $stockItem->quantity < $remaining
            $total += $stockItem->calculatePrice();
            $remaining -= $stockItem->onHand();
            $this->recordThat(new StockItemRemoved($stockItem->id(), $command->createdAt()));

            if ($remaining == 0) {
                break;
            }
        }

        $quantity = $this->onHand() - $command->quantity();
        $valuation = $this->valuation() - $total;

        $this->recordThat(new StockUpdated(
            $this->id(),
            $quantity,
            $valuation
        ));

        $this->recordThat(new StockQuantityApplied(
            $this->id(),
            $command->quantity(),
            Money::fromAmount($total, CurrencyCode::fromNative($this->currency())),
            $command->createdAt()
        ));
    }

    protected function whenStockCreated(StockCreated $event)
    {
        $this->state = \array_merge($this->state, $event->toPayload(), [
            'quantity' => 0,
            'valuation' => 0,
        ]);
    }

    protected function whenStockUpdated(StockUpdated $event)
    {
        $this->state = \array_merge($this->state, [
            'quantity' => $event->quantity(),
            'valuation' => $event->valuation(),
        ]);
    }

    private function isOutOfStock(): bool
    {
        return $this->state['quantity'] === 0;
    }

    private function onHand(): int
    {
        return $this->state['quantity'];
    }

    private function valuation(): float
    {
        return $this->state['valuation'];
    }

    private function currency(): string
    {
        return $this->state['currency'];
    }

    private function assertStock(int $quantity)
    {
        if ($this->isOutOfStock()) {
            throw new ProductOutOfStock('Product is out of stock.');
        }

        if ($this->onHand() < $quantity) {
            throw new InsufficientProductQuantity('Insufficient product quantity.');
        }
    }
}
