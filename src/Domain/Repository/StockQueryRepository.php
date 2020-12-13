<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\QueryRepository;
use Stock\Domain\StockId;

/**
 * @method void create(StockId $id, array $state)
 * @method void save(StockId $id, array $state)
 * @method void delete(StockId $id)
 * @method array|null findOne(StockId $id)
 * @method array findOneOrFail(StockId $id)
 */
interface StockQueryRepository extends QueryRepository
{
}
