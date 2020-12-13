<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\HyperfBridge\Repository\HyperfQueryRepository;
use Stock\Domain\Repository\StockQueryRepository;
use Stock\Infrastructure\Hyperf\Model\HyperfStock;

class HyperfStockQueryRepository extends HyperfQueryRepository implements StockQueryRepository
{
    public function __construct()
    {
        parent::__construct(new HyperfStock());
    }
}
