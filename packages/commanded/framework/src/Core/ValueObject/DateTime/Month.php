<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static Month JANUARY()
 * @method static Month FEBRUARY()
 * @method static Month MARCH()
 * @method static Month APRIL()
 * @method static Month MAY()
 * @method static Month JUNE()
 * @method static Month JULY()
 * @method static Month AUGUST()
 * @method static Month SEPTEMBER()
 * @method static Month OCTOBER()
 * @method static Month NOVEMBER()
 * @method static Month DECEMBER()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class Month extends Enum
{
    private const JANUARY = 'January';
    private const FEBRUARY = 'February';
    private const MARCH = 'March';
    private const APRIL = 'April';
    private const MAY = 'May';
    private const JUNE = 'June';
    private const JULY = 'July';
    private const AUGUST = 'August';
    private const SEPTEMBER = 'September';
    private const OCTOBER = 'October';
    private const NOVEMBER = 'November';
    private const DECEMBER = 'December';

    public function __construct($value)
    {
        Assert::string($value);

        parent::__construct(ucfirst(strtolower($value)));
    }

    public static function now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return static::fromString('now', $timezone);
    }

    public static function fromString(string $time, string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        $datetime = DateTime::fromString($time, $timezone);

        return new self($datetime->format('F'));
    }

    public static function fromOrdinal(int $value): self
    {
        Assert::range($value, 1, 12);
        $values = \array_values(static::toArray());

        return new self($values[$value - 1]);
    }

    /**
     * Returns a numeric representation of the Month.
     * 1 for January to 12 for December.
     */
    public function ordinalValue(): int
    {
        return \intval(\array_search($this->toNative(), \array_values(static::toArray()), true)) + 1;
    }
}
