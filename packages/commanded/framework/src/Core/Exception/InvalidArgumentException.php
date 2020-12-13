<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class InvalidArgumentException extends BadRequestException
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct($message ?? 'Invalid argument exception', $reason, $previous);
    }
}
