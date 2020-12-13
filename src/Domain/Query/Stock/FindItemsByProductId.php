<?php

declare(strict_types=1);

namespace Stock\Domain\Query\Stock;

use Commanded\Domain\Query\DomainQuery;
use Stock\Domain\ProductId;

final class FindItemsByProductId extends DomainQuery
{
    public const QUERY_NAME = 'findItemsByProductId';
    private ProductId $productId;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
