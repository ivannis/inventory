<?php

declare(strict_types=1);

namespace Stock\Domain\Exception;

use Commanded\Core\Exception\LogicException;

final class ProductOutOfStock extends LogicException
{
    public function __construct(string $message = 'Product out of stock.', \Throwable $previous = null)
    {
        parent::__construct($message, ErrorReasons::PRODUCT_OUT_OF_STOCK(), $previous);
    }
}
