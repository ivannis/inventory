<?php

declare(strict_types=1);

namespace Stock\Exception;

final class ProductOutOfStock extends BusinessException
{
    public function __construct(string $message = 'Product out of stock.')
    {
        parent::__construct($message, 'ProductOutOfStock');
    }
}
