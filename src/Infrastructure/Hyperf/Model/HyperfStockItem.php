<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Hyperf\Model;

class HyperfStockItem extends HyperfModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this
            ->setTable('stock_item')
            ->setTimestamps(false)
        ;
    }
}
