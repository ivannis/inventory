<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DateTimeImmutable;
use InvalidArgumentException;
use Commanded\Core\ValueObject\DateTime\DateConversion;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\DateTime\Hour;
use Commanded\Core\ValueObject\DateTime\Minute;
use Commanded\Core\ValueObject\DateTime\Month;
use Commanded\Core\ValueObject\DateTime\MonthDay;
use Commanded\Core\ValueObject\DateTime\Second;
use Commanded\Core\ValueObject\DateTime\TimeZone;
use Commanded\Core\ValueObject\DateTime\TimezoneName;
use Commanded\Core\ValueObject\DateTime\WeekDay;
use Commanded\Core\ValueObject\DateTime\Year;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method DateTimeImmutable toNative()
 * @method \Commanded\Core\ValueObject\Number\Integer diffInYears(DateTimeInterface $datetime = null, bool $absolute = true)
 */
trait PropertyTrait
{
    private ?Year $year = null;
    private ?Month $month = null;
    private ?MonthDay $day = null;
    private ?Hour $hour = null;
    private ?Minute $minute = null;
    private ?Second $second = null;
    private ?WeekDay $dayOfWeek = null;
    private ?int $micro = null;
    private ?int $dayOfYear = null;
    private ?int $weekOfYear = null;
    private ?int $daysInMonth = null;
    private ?int $weekOfMonth = null;
    private ?int $timestamp = null;
    private ?int $age = null;
    private ?int $quarter = null;
    private ?int $offset = null;
    private ?int $offsetHours = null;
    private ?bool $isDaylightSavingTime = null;
    private ?bool $isLocalTime = null;
    private ?bool $isUtc = null;
    private ?TimeZone $timezone = null;
    private ?TimeZoneName $timezoneName = null;

    public function year(): Year
    {
        if ($this->year == null) {
            $this->year = Year::fromNative($this->propertyValue('year'));
        }

        return $this->year;
    }

    public function month(): Month
    {
        if ($this->month == null) {
            $this->month = Month::fromOrdinal($this->propertyValue('month'));
        }

        return $this->month;
    }

    public function day(): MonthDay
    {
        if ($this->day == null) {
            $this->day = MonthDay::fromNative($this->propertyValue('day'));
        }

        return $this->day;
    }

    public function hour(): Hour
    {
        if ($this->hour == null) {
            $this->hour = Hour::fromNative($this->propertyValue('hour'));
        }

        return $this->hour;
    }

    public function minute(): Minute
    {
        if ($this->minute == null) {
            $this->minute = Minute::fromNative($this->propertyValue('minute'));
        }

        return $this->minute;
    }

    public function second(): Second
    {
        if ($this->second == null) {
            $this->second = Second::fromNative($this->propertyValue('second'));
        }

        return $this->second;
    }

    public function micro(): Integer
    {
        if ($this->micro == null) {
            $this->micro = Integer::fromNative($this->propertyValue('micro'));
        }

        return $this->micro;
    }

    public function dayOfWeek(): WeekDay
    {
        if ($this->dayOfWeek == null) {
            $this->dayOfWeek = WeekDay::fromOrdinal($this->propertyValue('dayOfWeek'));
        }

        return $this->dayOfWeek;
    }

    public function dayOfYear(): Integer
    {
        if ($this->dayOfYear == null) {
            $this->dayOfYear = Integer::fromNative($this->propertyValue('dayOfYear'));
        }

        return $this->dayOfYear;
    }

    public function weekOfYear(): Integer
    {
        if ($this->weekOfYear == null) {
            $this->weekOfYear = Integer::fromNative($this->propertyValue('weekOfYear'));
        }

        return $this->weekOfYear;
    }

    public function daysInMonth(): Integer
    {
        if ($this->daysInMonth == null) {
            $this->daysInMonth = Integer::fromNative($this->propertyValue('daysInMonth'));
        }

        return $this->daysInMonth;
    }

    public function timestamp(): Integer
    {
        if ($this->timestamp == null) {
            $this->timestamp = Integer::fromNative($this->propertyValue('timestamp'));
        }

        return $this->timestamp;
    }

    public function weekOfMonth(): Integer
    {
        if ($this->weekOfMonth == null) {
            $this->weekOfMonth = Integer::fromNative($this->propertyValue('weekOfMonth'));
        }

        return $this->weekOfMonth;
    }

    public function age(): Integer
    {
        if ($this->age == null) {
            $this->age = Integer::fromNative($this->propertyValue('age'));
        }

        return $this->age;
    }

    public function quarter(): Integer
    {
        if ($this->quarter == null) {
            $this->quarter = Integer::fromNative($this->propertyValue('quarter'));
        }

        return $this->quarter;
    }

    public function offset(): Integer
    {
        if ($this->offset == null) {
            $this->offset = Integer::fromNative($this->propertyValue('offset'));
        }

        return $this->offset;
    }

    public function offsetHours(): Integer
    {
        if ($this->offsetHours == null) {
            $this->offsetHours = Integer::fromNative($this->propertyValue('offsetHours'));
        }

        return $this->offsetHours;
    }

    public function isDaylightSavingTime(): bool
    {
        if ($this->isDaylightSavingTime == null) {
            $this->isDaylightSavingTime = $this->propertyValue('dst');
        }

        return $this->isDaylightSavingTime;
    }

    public function isLocalTime(): bool
    {
        if ($this->isLocalTime == null) {
            $this->isLocalTime = $this->propertyValue('local');
        }

        return $this->isLocalTime;
    }

    public function isUtc(): bool
    {
        if ($this->isUtc == null) {
            $this->isUtc = $this->propertyValue('utc');
        }

        return $this->isUtc;
    }

    public function timezone(): TimeZone
    {
        if ($this->timezone == null) {
            $this->timezone = TimeZone::fromNative($this->propertyValue('timezoneName'));
        }

        return $this->timezone;
    }

    public function timezoneName(): TimezoneName
    {
        if ($this->timezoneName == null) {
            $this->timezoneName = TimeZoneName::fromNative($this->propertyValue('timezoneName'));
        }

        return $this->timezoneName;
    }

    /**
     * Get a part of the object.
     *
     * @param string $name the property name to read
     * @throws \InvalidArgumentException
     * @return mixed the property value
     */
    private function propertyValue(string $name)
    {
        static $formats = [
            'year' => 'Y',
            'month' => 'n',
            'day' => 'j',
            'hour' => 'G',
            'minute' => 'i',
            'second' => 's',
            'micro' => 'u',
            'dayOfWeek' => 'N',
            'dayOfYear' => 'z',
            'weekOfYear' => 'W',
            'daysInMonth' => 't',
            'timestamp' => 'U',
        ];

        switch (true) {
            case isset($formats[$name]):
                return (int) $this->format($formats[$name]);
            case $name === 'weekOfMonth':
                return (int) ceil($this->propertyValue('day') / DateConversion::DAYS_PER_WEEK);
            case $name === 'age':
                return $this->diffInYears()->toNative();
            case $name === 'quarter':
                return (int) ceil($this->propertyValue('month') / 3);
            case $name === 'offset':
                return $this->toNative()->getOffset();
            case $name === 'offsetHours':
                return (int) (
                    $this->toNative()->getOffset() /
                    DateConversion::SECONDS_PER_MINUTE /
                    DateConversion::MINUTES_PER_HOUR
                );

            case $name === 'dst':
                return $this->format('I') === '1';
            case $name === 'local':
                return $this->propertyValue('offset') === $this->copy()
                    ->withTimezone(date_default_timezone_get())
                    ->propertyValue('offset');

            case $name === 'utc':
                return $this->propertyValue('offset') === 0;
            case $name === 'timezoneName':
                return $this->toNative()->getTimezone()->getName();
            default:
                throw new InvalidArgumentException(sprintf("Unknown property '%s'", $name));
        }
    }
}
