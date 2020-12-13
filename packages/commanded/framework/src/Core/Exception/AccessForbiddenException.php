<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class AccessForbiddenException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::ACCESS_FORBIDDEN(),
            $message ?? 'Access Forbidden',
            403,
            $previous
        );
    }
}
