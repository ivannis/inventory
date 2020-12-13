<?php

declare(strict_types=1);

namespace Stock\Domain\Command\Stock;

use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\StockItemId;

final class RemoveStockItem extends DomainCommand
{
    public const COMMAND_NAME = 'remove-stock-item';
    private StockItemId $id;

    public function __construct(StockItemId $id)
    {
        $this->id = $id;
    }

    public function id(): StockItemId
    {
        return $this->id;
    }
}
