<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class Time extends AbstractValueObject
{
    private Hour $hour;
    private Minute $minute;
    private Second $second;
    private TimeZone $timezone;

    public function __toString(): string
    {
        return $this->time();
    }

    public static function now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return static::fromString('now', $timezone);
    }

    public static function fromString(string $time, string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        $datetime = DateTime::fromString($time, $timezone);

        return new self([
            'time' => $datetime->toTimeString(),
            'timezone' => $timezone,
        ]);
    }

    public static function fromStringUTC(string $time): self
    {
        return static::fromString($time, DateTimeInterface::UTC_TIMEZONE);
    }

    public function withTimezone(string $timezone): self
    {
        $datetime = $this->toDateTime()->withTimezone($timezone);

        return new self([
            'time' => $datetime->toTimeString(),
            'timezone' => $timezone,
        ]);
    }

    public function withUTC(): self
    {
        return $this->withTimezone(DateTimeInterface::UTC_TIMEZONE);
    }

    public static function midnight(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return new self([
            'time' => '00:00:00',
            'timezone' => $timezone,
        ]);
    }

    public function hour(): Hour
    {
        return $this->hour;
    }

    public function minute(): Minute
    {
        return $this->minute;
    }

    public function second(): Second
    {
        return $this->second;
    }

    public function timezone(): TimeZone
    {
        return $this->timezone;
    }

    public function greaterThan(Time $time): bool
    {
        return $this->toDateTime()->greaterThan($time->toDateTime());
    }

    public function greaterThanOrEqual(Time $time): bool
    {
        return $this->toDateTime()->greaterThanOrEqual($time->toDateTime());
    }

    public function lessThan(Time $time): bool
    {
        return $this->toDateTime()->lessThan($time->toDateTime());
    }

    public function lessThanOrEqual(Time $time): bool
    {
        return $this->toDateTime()->lessThanOrEqual($time->toDateTime());
    }

    public function jsonSerialize()
    {
        return $this->withUTC()->toDateTime()->toTimeString();
    }

    protected function init($value): void
    {
        Assert::isArray($value);
        Assert::keyExists($value, 'time');

        $time = explode(':', $value['time']);
        $hour = (int) $time[0];
        $minute = isset($time[1]) ? (int) $time[1] : 0;
        $second = isset($time[2]) ? (int) $time[2] : 0;
        $timezone = $value['timezone'] ?? DateTimeInterface::DEFAULT_TIMEZONE;

        $this->hour = Hour::fromNative($hour);
        $this->minute = Minute::fromNative($minute);
        $this->second = Second::fromNative($second);
        $this->timezone = TimeZone::fromNative($timezone);
    }

    private function time(): string
    {
        return $this->toNative()['time'];
    }

    private function toDateTime(): DateTime
    {
        return DateTime::fromString(
            \sprintf('2010-01-01 %s', $this->time()),
            $this->timezone->toNative()
        );
    }
}
