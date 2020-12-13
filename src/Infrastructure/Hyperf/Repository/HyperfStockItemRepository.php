<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\HyperfBridge\Repository\HyperfRepository;
use Stock\Domain\Repository\StockItemRepository;
use Stock\Domain\StockItem;
use Stock\Infrastructure\Hyperf\Model\HyperfStockItem;

class HyperfStockItemRepository extends HyperfRepository implements StockItemRepository
{
    public function __construct(EventRecorder $eventRecorder)
    {
        parent::__construct(new HyperfStockItem(), StockItem::class, $eventRecorder);
    }
}
