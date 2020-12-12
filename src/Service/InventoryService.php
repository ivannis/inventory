<?php

declare(strict_types=1);

namespace Stock\Service;

use Hyperf\Utils\Collection;
use Stock\Model\InventoryHistory;

class InventoryService
{
    public function findHistoryByProductId(int $productId): Collection
    {
        return InventoryHistory::query()->where(['product_id' => $productId])->get();
    }
}
