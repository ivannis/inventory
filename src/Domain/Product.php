<?php

declare(strict_types=1);

namespace Stock\Domain;

use Commanded\Domain\Aggregate\AggregateRoot;
use Stock\Domain\Command\Product\CreateProduct;
use Stock\Domain\Event\Product\ProductCreated;

class Product extends AggregateRoot
{
    public function id(): ProductId
    {
        return ProductId::fromNative($this->id);
    }

    public function create(CreateProduct $command)
    {
        $this->recordThat(new ProductCreated($this->id(), $command->name()));
    }

    protected function whenProductCreated(ProductCreated $event)
    {
        $this->state = \array_merge($this->state, $event->toPayload());
    }
}
