<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\QueryRepository;
use Hyperf\Utils\Collection;
use Stock\Domain\StockId;
use Stock\Domain\StockItem;
use Stock\Domain\StockItemId;

/**
 * @method void create(StockItemId $id, array $state)
 * @method void save(StockItemId $id, array $state)
 * @method void delete(StockItemId $id)
 * @method array|null findOne(StockItemId $id)
 * @method array findOneOrFail(StockItemId $id)
 * @method Collection findBy(array $criteria = [], array $orderBy = [])
 */
interface StockItemQueryRepository extends QueryRepository
{
    /**
     * @return Collection|StockItem[]
     */
    public function findByStockId(StockId $stockId): Collection;
}
