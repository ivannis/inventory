<?php

declare(strict_types=1);

namespace Stock\Domain\Repository;

use Commanded\Domain\Repository\Repository;
use Stock\Domain\Product;
use Stock\Domain\ProductId;

/**
 * @method void create(Product $product)
 * @method void save(Product $product)
 * @method void delete(ProductId $id)
 * @method Product load(ProductId $id)
 */
interface ProductRepository extends Repository
{
}
