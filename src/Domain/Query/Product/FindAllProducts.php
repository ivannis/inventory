<?php

declare(strict_types=1);

namespace Stock\Domain\Query\Product;

use Commanded\Domain\Query\DomainQuery;

final class FindAllProducts extends DomainQuery
{
    public const QUERY_NAME = 'findAllProducts';
}
