<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\QueryRepository;
use Stock\Domain\InventoryHistoryId;

/**
 * @method void create(InventoryHistoryId $id, array $state)
 * @method void save(InventoryHistoryId $id, array $state)
 * @method void delete(InventoryHistoryId $id)
 * @method array|null findOne(InventoryHistoryId $id)
 * @method array findOneOrFail(InventoryHistoryId $id)
 */
interface InventoryHistoryQueryRepository extends QueryRepository
{
}
