<?php

declare(strict_types=1);

namespace Stock\Domain\Query;

use Commanded\Domain\Query\DomainQuery;
use Hyperf\Database\Model\ModelNotFoundException;
use Stock\Domain\Exception\ProductNotFound;
use Stock\Domain\ProductId;
use Stock\Domain\Query\Stock\FindItemsByProductId;
use Stock\Domain\Query\Stock\FindLastApplyMovementByProductId;
use Stock\Domain\Query\Stock\FindOneInventoryByProductId;
use Stock\Domain\Query\Stock\FindOneStockByProductId;
use Stock\Domain\Repository\InventoryHistoryQueryRepository;
use Stock\Domain\Repository\StockItemQueryRepository;
use Stock\Domain\Repository\StockQueryRepository;
use Stock\Domain\StockId;
use Stock\Domain\StockItem;

final class StockQueryHandler
{
    private StockQueryRepository $repository;
    private StockItemQueryRepository $items;
    private InventoryHistoryQueryRepository $inventory;

    public function __construct(
        StockQueryRepository $repository,
        StockItemQueryRepository $items,
        InventoryHistoryQueryRepository $inventory
    ) {
        $this->repository = $repository;
        $this->items = $items;
        $this->inventory = $inventory;
    }

    public function __invoke(DomainQuery $query)
    {
        switch ($query->messageName()) {
            case FindOneStockByProductId::QUERY_NAME:
                return $this->findOneStockByProductId($query);
            case FindOneInventoryByProductId::QUERY_NAME:
                return $this->findOneInventoryByProductId($query);
            case FindLastApplyMovementByProductId::QUERY_NAME:
                return $this->findLastApplyMovementByProductId($query);
            case FindItemsByProductId::QUERY_NAME:
                return $this->findItemsByProductId($query);
            default:
                throw new \UnexpectedValueException(
                    'Unknown query ' . $query->messageName()
                );
        }
    }

    public function findOneStockByProductId(FindOneStockByProductId $query)
    {
        return $this->findOneByProductId($query->productId());
    }

    public function findOneInventoryByProductId(FindOneInventoryByProductId $query)
    {
        $stock = $this->findOneByProductId($query->productId());

        return $this->inventory->findBy([
            'stockId' => $stock['id']
        ], ['createdAt' => 'asc']);
    }

    public function findLastApplyMovementByProductId(FindLastApplyMovementByProductId $query)
    {
        $stock = $this->findOneByProductId($query->productId());

        return $this->inventory->findBy([
            'stockId' => $stock['id'],
            'type' => 'application',
        ], ['createdAt' => 'desc'])->first();
    }

    public function findItemsByProductId(FindItemsByProductId $query)
    {
        $stock = $this->findOneByProductId($query->productId());

        return $this->items->findBy([
            'stockId' => $stock['id']
        ], ['createdAt' => 'desc']);
    }

    private function findOneByProductId(ProductId $productId)
    {
        try {
            return $this->repository->findOneByOrFail([
                'productId' => (string) $productId
            ]);
        } catch (ModelNotFoundException $e) {
            throw ProductNotFound::withId($productId);
        }
    }
}
