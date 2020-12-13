<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\String;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;

/**
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
abstract class StringLiteral extends AbstractValueObject
{
    protected function init($value): void
    {
        Assert::string($value);
    }
}
