<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Climate;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Natural;

/**
 * @method static static fromNative(int $value)
 */
class RelativeHumidity extends Natural
{
    public function __toString(): string
    {
        return \sprintf('%d %%', $this->toNative());
    }

    protected function init($value): void
    {
        parent::init($value);

        Assert::lessThanEq($value, 100);
    }
}
