<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Number;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static RoundingMode HALF_UP()
 * @method static RoundingMode HALF_DOWN()
 * @method static RoundingMode HALF_EVEN()
 * @method static RoundingMode HALF_ODD()
 * @method static static fromNative(int $value)
 * @method int toNative()
 */
final class RoundingMode extends Enum
{
    private const HALF_UP = PHP_ROUND_HALF_UP;
    private const HALF_DOWN = PHP_ROUND_HALF_DOWN;
    private const HALF_EVEN = PHP_ROUND_HALF_EVEN;
    private const HALF_ODD = PHP_ROUND_HALF_ODD;
}
