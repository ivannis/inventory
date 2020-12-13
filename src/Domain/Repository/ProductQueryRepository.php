<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\QueryRepository;
use Stock\Domain\ProductId;

/**
 * @method void create(ProductId $id, array $state)
 * @method void save(ProductId $id, array $state)
 * @method void delete(ProductId $id)
 * @method array|null findOne(ProductId $id)
 * @method array findOneOrFail(ProductId $id)
 */
interface ProductQueryRepository extends QueryRepository
{
}
