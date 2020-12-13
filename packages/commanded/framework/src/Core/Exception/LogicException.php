<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class LogicException extends UnprocessableEntityException
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct($message ?? 'Logic exception', $reason, $previous);
    }
}
