<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Real;

/**
 * @method static static fromNative(float $value)
 */
class Latitude extends Real
{
    private const MIN_LATITUDE = -90;
    private const MAX_LATITUDE = 90;

    protected function init($value): void
    {
        parent::init($value);

        Assert::range($value, self::MIN_LATITUDE, self::MAX_LATITUDE);
        Assert::latitude($value);
    }
}
