<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\DateTime\TimeZone;
use Commanded\Core\ValueObject\DateTime\WeekDay;

/**
 * @property WeekDay $dayOfWeek
 * @property TimeZone $timezone
 *
 * @method static DateTimeInterface now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 * @method static DateTimeInterface tomorrow(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 * @method static DateTimeInterface yesterday(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 *
 * @method string format(string $format)
 * @method string toDateString(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE)
 */
trait ComparisonTrait /* implements ComparableInterface */
{
    public static function weekendDays(): array
    {
        return [WeekDay::SATURDAY(), WeekDay::SUNDAY()];
    }

    public function notEquals(DateTimeInterface $other): bool
    {
        return ! $this->equals($other);
    }

    public function greaterThan(DateTimeInterface $other): bool
    {
        return $this->toNative() > $other->toNative();
    }

    public function greaterThanOrEqual(DateTimeInterface $other): bool
    {
        return $this->toNative() >= $other->toNative();
    }

    public function lessThan(DateTimeInterface $other): bool
    {
        return $this->toNative() < $other->toNative();
    }

    public function lessThanOrEqual(DateTimeInterface $other): bool
    {
        return $this->toNative() <= $other->toNative();
    }

    public function between(DateTimeInterface $other1, DateTimeInterface $other2, bool $equal = true): bool
    {
        if ($other1->greaterThan($other2)) {
            $temp = $other1;
            $other1 = $other2;
            $other2 = $temp;
        }

        if ($equal) {
            return $this->greaterThanOrEqual($other1) && $this->lessThanOrEqual($other2);
        }

        return $this->greaterThan($other1) && $this->lessThan($other2);
    }

    public function closest(DateTimeInterface $other1, DateTimeInterface $other2): self
    {
        return $this->diffInSeconds($other1) < $this->diffInSeconds($other2) ? $other1 : $other2;
    }

    public function farthest(DateTimeInterface $other1, DateTimeInterface $other2): self
    {
        return $this->diffInSeconds($other1) > $this->diffInSeconds($other2) ? $other1 : $other2;
    }

    public function min(DateTimeInterface $other = null): self
    {
        $other = ($other === null) ? static::now($this->timezoneName()->toNative()) : $other;

        return $this->lessThan($other) ? $this : $other;
    }

    public function max(DateTimeInterface $other = null): self
    {
        $other = ($other === null) ? static::now($this->timezoneName()->toNative()) : $other;

        return $this->greaterThan($other) ? $this : $other;
    }

    public function isWeekday(): bool
    {
        return ! $this->isWeekend();
    }

    public function isWeekend(): bool
    {
        return in_array(
            $this->dayOfWeek()->toNative(),
            array_map(function (WeekDay $day) {
                return $day->toNative();
            }, self::weekendDays()),
            true
        );
    }

    public function isYesterday(): bool
    {
        return $this->toDateString() == static::yesterday($this->timezoneName()->toNative())->toDateString();
    }

    public function isToday(): bool
    {
        return $this->toDateString() == static::now($this->timezoneName()->toNative())->toDateString();
    }

    public function isTomorrow(): bool
    {
        return $this->toDateString() == static::tomorrow($this->timezoneName()->toNative())->toDateString();
    }

    public function isNextWeek(): bool
    {
        return $this->format('W o') == static::now($this->timezoneName()->toNative())
            ->addWeek()
            ->format('W o')
        ;
    }

    public function isLastWeek(): bool
    {
        return $this->format('W o') == static::now($this->timezoneName()->toNative())
            ->subWeek()
            ->format('W o')
        ;
    }

    public function isNextMonth(): bool
    {
        return $this->format('m Y') == static::now($this->timezoneName()->toNative())
            ->addMonth()
            ->format('m Y')
        ;
    }

    public function isLastMonth(): bool
    {
        return $this->format('m Y') == static::now($this->timezoneName()->toNative())
            ->subMonth()
            ->format('m Y')
        ;
    }

    public function isNextYear(): bool
    {
        return $this->year()->equals(
            static::now($this->timezoneName()->toNative())->addYear()->year()
        );
    }

    public function isLastYear(): bool
    {
        return $this->year()->equals(
            static::now($this->timezoneName()->toNative())->subYear()->year()
        );
    }

    public function isFuture(): bool
    {
        return $this->greaterThan(static::now($this->timezoneName()->toNative()));
    }

    public function isPast(): bool
    {
        return $this->lessThan(static::now($this->timezoneName()->toNative()));
    }

    public function isLeapYear(): bool
    {
        return $this->format('L') === '1';
    }

    public function isSameDay(DateTimeInterface $other): bool
    {
        return $this->toDateString() == $other->toDateString();
    }

    public function isSunday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::SUNDAY());
    }

    public function isMonday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::MONDAY());
    }

    public function isTuesday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::TUESDAY());
    }

    public function isWednesday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::WEDNESDAY());
    }

    public function isThursday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::THURSDAY());
    }

    public function isFriday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::FRIDAY());
    }

    public function isSaturday(): bool
    {
        return $this->dayOfWeek()->equals(WeekDay::SATURDAY());
    }

    public function isThisWeek(): bool
    {
        return static::now($this->timezoneName()->toNative())->format('W o') == $this->format('W o');
    }

    public function isThisMonth(): bool
    {
        return static::now($this->timezoneName()->toNative())->format('m Y') == $this->format('m Y');
    }

    public function isThisYear(): bool
    {
        return static::now($this->timezoneName()->toNative())->format('Y') == $this->format('Y');
    }

    public function isBirthday(DateTimeInterface $other = null): bool
    {
        if ($other === null) {
            $other = static::now($this->timezoneName()->toNative());
        }

        return $this->format('md') == $other->format('md');
    }

    public function wasWithinLast(string $timeInterval): bool
    {
        $now = static::now($this->timezoneName()->toNative());
        $interval = $now->copy()->modify('-' . $timeInterval);
        $thisTime = $this->format('U');

        return $thisTime >= $interval->format('U') && $thisTime <= $now->format('U');
    }

    public function isWithinNext(string $timeInterval): bool
    {
        $now = static::now($this->timezoneName()->toNative());
        $interval = $now->copy()->modify('+' . $timeInterval);
        $thisTime = $this->format('U');

        return $thisTime <= $interval->format('U') && $thisTime >= $now->format('U');
    }
}
