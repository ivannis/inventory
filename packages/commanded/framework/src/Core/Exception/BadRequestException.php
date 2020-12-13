<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class BadRequestException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::BAD_REQUEST(),
            $message ?? 'Bad Request',
            400,
            $previous
        );
    }
}
