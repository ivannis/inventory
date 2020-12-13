<?php

declare(strict_types=1);

namespace Stock\Domain\Exception;

use Commanded\Core\Exception\LogicException;

final class InsufficientProductQuantity extends LogicException
{
    public function __construct(string $message = 'Insufficient product quantity.', \Throwable $previous = null)
    {
        parent::__construct($message, ErrorReasons::INSUFFICIENT_PRODUCT_QUANTITY(), $previous);
    }
}
