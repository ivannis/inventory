<?php

declare(strict_types=1);

namespace Stock\Domain\Command;

use Commanded\Domain\Command\DomainCommand;
use Stock\Domain\Command\Product\CreateProduct;
use Stock\Domain\Product;
use Stock\Domain\Repository\ProductRepository;

final class ProductCommandHandler
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DomainCommand $command)
    {
        switch ($command->messageName()) {
            case CreateProduct::COMMAND_NAME:
                $this->onCreate($command);
                break;
            default:
                throw new \UnexpectedValueException(
                    'Unknown command ' . $command->messageName()
                );
        }
    }

    public function onCreate(CreateProduct $command)
    {
        $product = Product::fromId($command->id());
        $product->create($command);

        $this->repository->create($product);
    }
}
