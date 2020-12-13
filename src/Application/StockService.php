<?php

declare(strict_types=1);

namespace Stock\Application;

use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\Money;
use Commanded\Domain\MessageBus;
use Hyperf\Utils\Collection;
use Stock\Domain\Command\Stock\AddStockItem;
use Stock\Domain\Command\Stock\ApplyQuantityToStock;
use Stock\Domain\ProductId;
use Stock\Domain\Query\Stock\FindItemsByProductId;
use Stock\Domain\Query\Stock\FindLastApplyMovementByProductId;
use Stock\Domain\Query\Stock\FindOneInventoryByProductId;
use Stock\Domain\Query\Stock\FindOneStockByProductId;
use Stock\Domain\StockId;

class StockService
{
    private MessageBus $bus;

    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    public function addItem(ProductId $productId, int $quantity, Money $unitPrice, DateTime $createdAt)
    {
        $stock = $this->findOneByProductId($productId);

        $this->bus->dispatch(new AddStockItem(
            StockId::fromNative($stock['id']),
            $quantity,
            $unitPrice,
            $createdAt
        ));
    }

    public function applyQuantity(ProductId $productId, int $quantity, DateTime $createdAt)
    {
        $stock = $this->findOneByProductId($productId);

        $this->bus->dispatch(new ApplyQuantityToStock(
            StockId::fromNative($stock['id']),
            $quantity,
            $createdAt
        ));
    }

    public function findInventoryByProductId(ProductId $productId): Collection
    {
        return $this->bus->execute(new FindOneInventoryByProductId($productId));
    }

    public function findLastApplyMovementByProductId(ProductId $productId): array
    {
        return $this->bus->execute(new FindLastApplyMovementByProductId($productId));
    }

    public function findOneByProductId(ProductId $productId): array
    {
        return $this->bus->execute(new FindOneStockByProductId($productId));
    }

    public function findItemsByProductId(ProductId $productId): Collection
    {
        return $this->bus->execute(new FindItemsByProductId($productId));
    }
}
