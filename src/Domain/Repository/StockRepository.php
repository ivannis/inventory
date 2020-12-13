<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\Repository;
use Stock\Domain\Stock;
use Stock\Domain\StockId;

/**
 * @method void create(Stock $stock)
 * @method void save(Stock $stock)
 * @method void delete(StockId $id)
 * @method Stock load(StockId $id)
 */
interface StockRepository extends Repository
{
}
