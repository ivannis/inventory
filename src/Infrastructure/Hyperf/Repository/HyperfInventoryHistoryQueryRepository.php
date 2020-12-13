<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\HyperfBridge\Repository\HyperfQueryRepository;
use Stock\Domain\InventoryHistory;
use Stock\Domain\Repository\InventoryHistoryQueryRepository;
use Stock\Infrastructure\Hyperf\Model\HyperfInventoryHistory;

class HyperfInventoryHistoryQueryRepository extends HyperfQueryRepository implements InventoryHistoryQueryRepository
{
    public function __construct()
    {
        parent::__construct(new HyperfInventoryHistory());
    }
}
