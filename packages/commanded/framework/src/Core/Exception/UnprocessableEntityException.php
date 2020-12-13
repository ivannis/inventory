<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class UnprocessableEntityException extends Exception
{
    public function __construct(string $message = null, ?ErrorReason $reason = null, \Throwable $previous = null)
    {
        parent::__construct(
            $reason ?? ErrorReason::UNPROCESSABLE_ENTITY(),
            $message ?? 'Unprocessable Entity',
            422,
            $previous
        );
    }
}
