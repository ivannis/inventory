<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\HyperfBridge\Repository\HyperfRepository;
use Stock\Domain\Repository\StockRepository;
use Stock\Domain\Stock;
use Stock\Infrastructure\Hyperf\Model\HyperfStock;

class HyperfStockRepository extends HyperfRepository implements StockRepository
{
    public function __construct(EventRecorder $eventRecorder)
    {
        parent::__construct(new HyperfStock(), Stock::class, $eventRecorder);
    }
}
