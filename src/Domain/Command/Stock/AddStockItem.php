<?php

declare(strict_types=1);

namespace Stock\Domain\Command\Stock;

use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\Money;
use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\StockId;

final class AddStockItem extends DomainCommand
{
    public const COMMAND_NAME = 'add-stock-item';
    private StockId $stockId;
    private int $quantity;
    private Money $unitPrice;
    private DateTime $createdAt;

    public function __construct(
        StockId $stockId,
        int $quantity,
        Money $unitPrice,
        DateTime $createdAt
    ) {
        $this->stockId = $stockId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->createdAt = $createdAt;
    }

    public function stockId(): StockId
    {
        return $this->stockId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
}
