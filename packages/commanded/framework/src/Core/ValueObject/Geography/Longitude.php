<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Real;

/**
 * @method static static fromNative(float $value)
 */
class Longitude extends Real
{
    private const MIN_LONGITUDE = -180;
    private const MAX_LONGITUDE = 180;

    protected function init($value): void
    {
        parent::init($value);

        Assert::range($value, self::MIN_LONGITUDE, self::MAX_LONGITUDE);
        Assert::longitude($value);
    }
}
