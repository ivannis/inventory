<?php

declare(strict_types=1);

namespace Stock\Exception;

class BusinessException extends \Exception
{
    private string $reason;

    public function __construct(string $message = '', string $reason, int $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->reason = $reason;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
