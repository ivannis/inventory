<?php

declare(strict_types=1);

namespace Stock\Application;

use Commanded\Domain\MessageBus;
use Hyperf\Utils\Collection;
use Stock\Domain\Command\Product\CreateProduct;
use Stock\Domain\ProductId;
use Stock\Domain\Query\Product\FindAllProducts;

class ProductService
{
    private MessageBus $bus;

    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    public function create(ProductId $productId, string $name)
    {
        $this->bus->dispatch(new CreateProduct($productId, $name));
    }

    public function findAll(): Collection
    {
        return $this->bus->execute(new FindAllProducts());
    }
}
