<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\Repository;
use Stock\Domain\StockItem;
use Stock\Domain\StockItemId;

/**
 * @method void create(StockItem $stock)
 * @method void save(StockItem $stock)
 * @method void delete(StockItemId $id)
 * @method StockItem load(StockItemId $id)
 */
interface StockItemRepository extends Repository
{
}
