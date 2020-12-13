<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Number;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(int $value)
 */
class Natural extends Integer
{
    protected function init($value): void
    {
        Assert::natural($value);
    }
}
