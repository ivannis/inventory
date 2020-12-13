<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static DistanceUnit METER()
 * @method static DistanceUnit FOOT()
 * @method static DistanceUnit KILOMETER()
 * @method static DistanceUnit MILE()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class DistanceUnit extends Enum
{
    private const FOOT = 'ft';
    private const METER = 'm';
    private const KILOMETER = 'km';
    private const MILE = 'mi';

    public function __construct($value)
    {
        Assert::string($value);

        parent::__construct(strtolower($value));
    }
}
