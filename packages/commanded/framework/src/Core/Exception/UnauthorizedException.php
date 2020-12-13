<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::UNAUTHORIZED(),
            $message ?? 'Unauthorized',
            401,
            $previous
        );
    }
}
