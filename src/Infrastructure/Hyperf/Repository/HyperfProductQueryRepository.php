<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Repository;

use Commanded\HyperfBridge\Repository\HyperfQueryRepository;
use Stock\Domain\Repository\ProductQueryRepository;
use Stock\Infrastructure\Hyperf\Model\HyperfProduct;

class HyperfProductQueryRepository extends HyperfQueryRepository implements ProductQueryRepository
{
    public function __construct()
    {
        parent::__construct(new HyperfProduct());
    }
}
