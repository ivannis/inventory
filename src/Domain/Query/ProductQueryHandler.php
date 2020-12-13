<?php

declare(strict_types=1);

namespace Stock\Domain\Query;

use Commanded\Domain\Query\DomainQuery;
use Stock\Domain\Query\Product\FindAllProducts;
use Stock\Domain\Repository\ProductQueryRepository;

final class ProductQueryHandler
{
    private ProductQueryRepository $repository;

    public function __construct(ProductQueryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DomainQuery $query)
    {
        switch ($query->messageName()) {
            case FindAllProducts::QUERY_NAME:
                return $this->findAll($query);
            default:
                throw new \UnexpectedValueException(
                    'Unknown query ' . $query->messageName()
                );
        }
    }

    public function findAll(FindAllProducts $query)
    {
        return $this->repository->findAll(['createdAt' => 'asc']);
    }
}
