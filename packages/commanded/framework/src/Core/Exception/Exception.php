<?php

declare(strict_types=1);

namespace Commanded\Core\Exception;

class Exception extends \Exception
{
    protected ErrorReason $reason;

    public function __construct(
        ErrorReason $reason,
        string $message = null,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message ?? $reason, $code, $previous);

        $this->reason = $reason;
    }

    public function getReason(): ErrorReason
    {
        return $this->reason;
    }
}
