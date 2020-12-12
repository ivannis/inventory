<?php

declare(strict_types=1);

namespace Stock\Exception;

final class InsufficientProductQuantity extends BusinessException
{
    public function __construct(string $message = 'Insufficient product quantity.')
    {
        parent::__construct($message, 'InsufficientProductQuantity');
    }
}
