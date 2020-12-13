<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface as DateTime;

trait InstanceTrait /* implements InstantiableInterface */
{
    /** @var array */
    protected static $_lastErrors = [];

    public function copy(): self
    {
        return static::fromDateTime($this->toNative());
    }

    public static function fromString(string $time = 'now', string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::instance($time, $timezone);
    }

    public static function fromStringUTC(string $time): self
    {
        return static::fromString($time, DateTime::UTC_TIMEZONE);
    }

    public static function now(string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::instance('now', $timezone);
    }

    public static function today(string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::instance('midnight', $timezone);
    }

    public static function tomorrow(string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::instance('tomorrow, midnight', $timezone);
    }

    public static function yesterday(string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::instance('yesterday, midnight', $timezone);
    }

    public static function maxValue(): self
    {
        return static::fromTimestampUTC(PHP_INT_MAX);
    }

    public static function minValue(): self
    {
        $max = PHP_INT_SIZE === 4 ? PHP_INT_MAX : PHP_INT_MAX / 10;

        return static::fromTimestampUTC(~$max);
    }

    public static function create(
        int $year = null,
        int $month = null,
        int $day = null,
        int $hour = null,
        int $minute = null,
        int $second = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self {
        $year = (int) ($year === null ? date('Y') : $year);
        $month = (int) ($month === null ? date('n') : $month);
        $day = (int) ($day === null ? date('j') : $day);

        if ($hour === null) {
            $hour = (int) date('G');
            $minute = (int) ($minute === null ? date('i') : $minute);
            $second = (int) ($second === null ? date('s') : $second);
        } else {
            $minute = (int) ($minute === null ? 0 : $minute);
            $second = (int) ($second === null ? 0 : $second);
        }

        $instance = static::fromFormat(
            'Y-n-j G:i:s',
            sprintf('%s-%s-%s %s:%02s:%02s', 0, $month, $day, $hour, $minute, $second),
            $timezone
        );

        return $instance->addYears($year);
    }

    public static function fromDate(
        int $year = null,
        int $month = null,
        int $day = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self {
        return static::create($year, $month, $day, null, null, null, $timezone);
    }

    public static function fromTime(
        int $hour = null,
        int $minute = null,
        int $second = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self {
        return static::create(null, null, null, $hour, $minute, $second, $timezone);
    }

    public static function fromFormat(
        string $format,
        string $time,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self {
        if ($timezone !== null) {
            $datetime = DateTimeImmutable::createFromFormat($format, $time, static::createTimezone($timezone));
        } else {
            $datetime = DateTimeImmutable::createFromFormat($format, $time);
        }

        $errors = DateTimeImmutable::getLastErrors();
        if ($datetime == false) {
            throw new InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
        }

        $datetime = static::fromDateTime($datetime);
        static::$_lastErrors = $errors;

        return $datetime;
    }

    public static function fromTimestamp(int $timestamp, string $timezone = DateTime::DEFAULT_TIMEZONE): self
    {
        return static::now($timezone)->setTimestamp($timestamp);
    }

    public static function fromTimestampUTC(int $timestamp): self
    {
        return static::instance('@' . $timestamp);
    }

    public static function lastErrors(): array
    {
        if (empty(static::$_lastErrors)) {
            return DateTimeImmutable::getLastErrors();
        }

        return static::$_lastErrors;
    }

    /**
     * @return static
     */
    abstract protected static function instance(string $time, string $timezone = DateTime::DEFAULT_TIMEZONE);

    protected static function fromDateTime(DateTimeInterface $datetime): self
    {
        return static::instance(
            $datetime->format('Y-m-d H:i:s.u'),
            $datetime->getTimezone()->getName()
        );
    }

    private static function createTimezone(string $timezone = DateTime::DEFAULT_TIMEZONE): DateTimeZone
    {
        if ($timezone !== null) {
            return new DateTimeZone($timezone);
        }

        return new DateTimeZone(date_default_timezone_get());
    }
}
