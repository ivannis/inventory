<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static static fromNative(string $value)
 */
class QueryString extends StringLiteral
{
    protected function init($value): void
    {
        Assert::queryString($value);
    }
}
