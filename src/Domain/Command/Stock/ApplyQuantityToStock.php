<?php

declare(strict_types=1);

namespace Stock\Domain\Command\Stock;

use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\StockId;
use Webmozart\Assert\Assert;

final class ApplyQuantityToStock extends DomainCommand
{
    public const COMMAND_NAME = 'apply-quantity-to-stock';
    private StockId $stockId;
    private int $quantity;
    private DateTime $createdAt;

    public function __construct(
        StockId $stockId,
        int $quantity,
        DateTime $createdAt
    ) {
        Assert::greaterThan($quantity, 0);

        $this->stockId = $stockId;
        $this->quantity = $quantity;
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

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
}
