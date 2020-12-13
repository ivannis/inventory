<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\HyperfBridge\Repository\HyperfQueryRepository;
use Hyperf\Utils\Collection;
use Stock\Domain\Repository\StockItemQueryRepository;
use Stock\Domain\StockId;
use Stock\Domain\StockItem;
use Stock\Infrastructure\Hyperf\Model\HyperfStockItem;

class HyperfStockItemQueryRepository extends HyperfQueryRepository implements StockItemQueryRepository
{
    public function __construct()
    {
        parent::__construct(new HyperfStockItem());
    }

    public function findByStockId(StockId $stockId): Collection
    {
        /** @var Collection $collection */
        $collection = $this->findBy(['stockId' => (string) $stockId], ['createdAt' => 'asc']);

        return $collection->map(function (array $item) {
            return StockItem::fromSnapshot([
                'id' => $item['id'],
                'version' => 0,
                'state' => $item,
            ]);
        });
    }
}
