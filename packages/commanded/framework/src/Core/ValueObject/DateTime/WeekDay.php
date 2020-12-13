<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static WeekDay MONDAY()
 * @method static WeekDay TUESDAY()
 * @method static WeekDay WEDNESDAY()
 * @method static WeekDay THURSDAY()
 * @method static WeekDay FRIDAY()
 * @method static WeekDay SATURDAY()
 * @method static WeekDay SUNDAY()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class WeekDay extends Enum
{
    private const MONDAY = 'Monday';
    private const TUESDAY = 'Tuesday';
    private const WEDNESDAY = 'Wednesday';
    private const THURSDAY = 'Thursday';
    private const FRIDAY = 'Friday';
    private const SATURDAY = 'Saturday';
    private const SUNDAY = 'Sunday';

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

        return new self($datetime->format('l'));
    }

    public static function fromOrdinal(int $value): self
    {
        Assert::range($value, 1, 7);
        $values = array_values(static::toArray());

        return static::fromNative($values[$value - 1]);
    }

    /**
     * Returns a numeric representation of the Day.
     * 1 for Montday to 7 for Sunday.
     */
    public function ordinalValue(): int
    {
        return \intval(\array_search($this->toNative(), \array_values(static::toArray()), true)) + 1;
    }
}
