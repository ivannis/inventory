<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::NOT_FOUND(),
            $message ?? 'Not found',
            404,
            $previous
        );
    }
}
