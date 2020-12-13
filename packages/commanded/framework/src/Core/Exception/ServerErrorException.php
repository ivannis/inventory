<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class ServerErrorException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::SERVER_ERROR(),
            $message ?? 'Server error',
            500,
            $previous
        );
    }
}
