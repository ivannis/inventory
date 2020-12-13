<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method static static fromNative(int $value)
 */
class Port extends Integer
{
    protected function init($value): void
    {
        Assert::port($value);
    }
}
