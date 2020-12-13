<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\Core\Messaging\Event\Recorder\EventRecorder;
use Commanded\HyperfBridge\Repository\HyperfRepository;
use Stock\Domain\Product;
use Stock\Domain\Repository\ProductRepository;
use Stock\Infrastructure\Hyperf\Model\HyperfProduct;

class HyperfProductRepository extends HyperfRepository implements ProductRepository
{
    public function __construct(EventRecorder $eventRecorder)
    {
        parent::__construct(new HyperfProduct(), Product::class, $eventRecorder);
    }
}
