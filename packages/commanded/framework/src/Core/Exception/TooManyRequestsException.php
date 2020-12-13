<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class TooManyRequestsException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::TOO_MANY_REQUESTS(),
            $message ?? 'Too many requests',
            429,
            $previous
        );
    }
}
