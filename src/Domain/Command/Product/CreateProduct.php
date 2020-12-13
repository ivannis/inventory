<?php

declare(strict_types=1);

namespace Stock\Domain\Command\Product;

use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\ProductId;

final class CreateProduct extends DomainCommand
{
    public const COMMAND_NAME = 'create-product';
    private ProductId $id;
    private string $name;

    public function __construct(ProductId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
