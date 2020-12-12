<?php

declare(strict_types=1);

namespace Stock\Service;

use Hyperf\Utils\Collection;
use Stock\Model\Product;

class ProductService
{
    public function create(string $name): Product
    {
        $product = new Product(['name' => $name]);
        $product->save();

        return $product;
    }

    public function findAll(): Collection
    {
        return Product::all();
    }
}
