<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(string $value)
 */
class HostName extends Domain
{
    protected function init($value): void
    {
        Assert::hostName($value);
    }
}
