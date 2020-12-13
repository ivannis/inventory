<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DatePeriod;
use DateTimeImmutable;
use Commanded\Core\ValueObject\DateTime\DateConversion;
use Commanded\Core\ValueObject\DateTime\DateInterval;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method static DateTimeInterface now($timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 * @method DateTimeImmutable toNative()
 */
trait DifferenceTrait /* implements DifferentiableInterface */
{
    public function diffInYears(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        $datetime = $datetime === null ? static::now($this->timezoneName()->toNative()) : $datetime;

        return Integer::fromNative(
            (int) $this->toNative()->diff($datetime->toNative(), $absolute)->format('%r%y')
        );
    }

    public function diffInMonths(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        $datetime = $datetime === null ? static::now($this->timezoneName()->toNative()) : $datetime;

        $diffInYears = $this->diffInYears($datetime, $absolute)->toNative();
        $diffInMonths = (int) $this->toNative()->diff($datetime->toNative(), $absolute)->format('%r%m');

        return Integer::fromNative(
            (int) ($diffInYears * DateConversion::MONTHS_PER_YEAR + $diffInMonths)
        );
    }

    public function diffInWeeks(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return Integer::fromNative(
            (int) ($this->diffInDays($datetime, $absolute)->toNative() / DateConversion::DAYS_PER_WEEK)
        );
    }

    public function diffInDays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        $datetime = $datetime === null ? static::now($this->timezoneName()->toNative()) : $datetime;

        return Integer::fromNative(
            (int) $this->toNative()->diff($datetime->toNative(), $absolute)->format('%r%a')
        );
    }

    public function diffInDaysFiltered(
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return $this->diffFiltered(DateInterval::fromDays(1), $callback, $datetime, $absolute);
    }

    public function diffInHoursFiltered(
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return $this->diffFiltered(DateInterval::fromHours(1), $callback, $datetime, $absolute);
    }

    public function diffFiltered(
        DateInterval $interval,
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        $start = $this;
        $end = $datetime === null ? static::now($this->timezoneName()->toNative()) : $datetime;
        $inverse = false;

        if ($end->lessThan($start)) {
            $start = $end;
            $end = $this;
            $inverse = true;
        }

        $period = new DatePeriod($start->toNative(), $interval->toDateInterval(), $end->toNative());
        $vals = array_filter(iterator_to_array($period), function (\DateTimeInterface $date) use ($callback) {
            return $callback(static::fromDateTime($date));
        });

        $diff = count($vals);

        return Integer::fromNative($inverse && ! $absolute ? -$diff : $diff);
    }

    public function diffInWeekdays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return $this->diffInDaysFiltered(function (DateTimeInterface $date) {
            return $date->isWeekday();
        }, $datetime, $absolute);
    }

    public function diffInWeekendDays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return $this->diffInDaysFiltered(function (DateTimeInterface $date) {
            return $date->isWeekend();
        }, $datetime, $absolute);
    }

    public function diffInHours(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return Integer::fromNative(
            (int) ($this->diffInSeconds($datetime, $absolute)->toNative() /
            DateConversion::SECONDS_PER_MINUTE /
            DateConversion::MINUTES_PER_HOUR)
        );
    }

    public function diffInMinutes(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        return Integer::fromNative(
            (int) ($this->diffInSeconds($datetime, $absolute)->toNative() / DateConversion::SECONDS_PER_MINUTE)
        );
    }

    public function diffInSeconds(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): Integer {
        $datetime = ($datetime === null) ? static::now($this->timezoneName()->toNative()) : $datetime;
        $value = $datetime->toNative()->getTimestamp() - $this->toNative()->getTimestamp();

        return Integer::fromNative($absolute ? abs($value) : $value);
    }

    public function secondsSinceMidnight(): Integer
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    public function secondsUntilEndOfDay(): Integer
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }

    public static function fromNow(DateTimeInterface $datetime): DateInterval
    {
        $timeNow = static::now();

        return DateInterval::fromDateInterval($timeNow->toNative()->diff($datetime->toNative()));
    }
}
