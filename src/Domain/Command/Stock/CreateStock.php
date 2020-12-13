<?php

declare(strict_types=1);

namespace Stock\Domain\Command\Stock;

use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\ProductId;
use Stock\Domain\StockId;

final class CreateStock extends DomainCommand
{
    public const COMMAND_NAME = 'create-stock';
    private StockId $id;
    private ProductId $productId;
    private CurrencyCode $currency;

    public function __construct(StockId $id, ProductId $productId, CurrencyCode $currency)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->currency = $currency;
    }

    public function id(): StockId
    {
        return $this->id;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function currency(): CurrencyCode
    {
        return $this->currency;
    }
}
