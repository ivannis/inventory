<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DateTimeImmutable;
use Commanded\Core\ValueObject\DateTime\DateConversion;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\DateTime\WeekDay;

/**
 * @method DateTimeImmutable toNative()
 * @method self copy()
 */
trait ModifierTrait /* implements ModificableInterface */
{
    public static function weekStartsAt(): WeekDay
    {
        return WeekDay::MONDAY();
    }

    public static function weekEndsAt(): WeekDay
    {
        return WeekDay::SUNDAY();
    }

    public function modify(string $relative): self
    {
        return static::fromDateTime($this->toNative()->modify($relative));
    }

    public function setDate(int $year, int $month, int $day): self
    {
        return static::fromDateTime(
            $this->toNative()->modify('+0 day')->setDate($year, $month, $day)
        );
    }

    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0): self
    {
        return static::fromDateTime(
            $this->setDate($year, $month, $day)->toNative()->setTime($hour, $minute, $second)
        );
    }

    public function setTime(int $hour, int $minute, int $second = 0): self
    {
        return static::fromDateTime(
            $this->toNative()->setTime($hour, $minute, $second)
        );
    }

    public function setTimeFromTimeString(string $time): self
    {
        $time = explode(':', $time);
        $hour = (int) $time[0];
        $minute = isset($time[1]) ? (int) $time[1] : 0;
        $second = isset($time[2]) ? (int) $time[2] : 0;

        return $this->setTime($hour, $minute, $second);
    }

    public function withTimezone(string $timezone): self
    {
        $timezone = $this->createTimeZone($timezone);
        $date = $this->toNative()->setTimezone($timezone);

        // https://bugs.php.net/bug.php?id=72338
        // this is workaround for this bug
        $date->getTimestamp();

        return static::fromDateTime($date);
    }

    public function withUTC(): self
    {
        return $this->withTimezone(DateTimeInterface::UTC_TIMEZONE);
    }

    public function setTimestamp(int $value): self
    {
        return static::fromDateTime(
            $this->toNative()->setTimestamp($value)
        );
    }

    public function setYear(int $value): self
    {
        return $this->setDate($value, $this->month()->ordinalValue(), $this->day()->toNative());
    }

    public function setMonth(int $value): self
    {
        return $this->setDate($this->year()->toNative(), $value, $this->day()->toNative());
    }

    public function setDay(int $value): self
    {
        return $this->setDate($this->year()->toNative(), $this->month()->ordinalValue(), $value);
    }

    public function setHour(int $value): self
    {
        return $this->setTime($value, $this->minute()->toNative(), $this->second()->toNative());
    }

    public function setMinute(int $value): self
    {
        return $this->setTime($this->hour()->toNative(), $value, $this->second()->toNative());
    }

    public function setSecond(int $value): self
    {
        return $this->setTime($this->hour()->toNative(), $this->minute()->toNative(), $value);
    }

    public function addYears(int $value): self
    {
        return $this->modify($value . ' year');
    }

    public function addYear(): self
    {
        return $this->addYears(1);
    }

    public function subYears(int $value): self
    {
        return $this->addYears(-1 * $value);
    }

    public function subYear(): self
    {
        return $this->subYears(1);
    }

    public function addMonths(int $value): self
    {
        $date = $this->modify($value . ' month');

        if (! $date->day()->equals($this->day())) {
            return $date->modify('last day of previous month');
        }

        return $date;
    }

    public function addMonth(): self
    {
        return $this->addMonths(1);
    }

    public function subMonths(int $value): self
    {
        return $this->addMonths(-1 * $value);
    }

    public function subMonth(): self
    {
        return $this->subMonths(1);
    }

    public function addMonthsWithOverflow(int $value): self
    {
        return $this->modify((int) $value . ' month');
    }

    public function addMonthWithOverflow(): self
    {
        return $this->modify('1 month');
    }

    public function subMonthsWithOverflow(int $value): self
    {
        return $this->addMonthsWithOverflow(-1 * $value);
    }

    public function subMonthWithOverflow(): self
    {
        return $this->subMonthsWithOverflow(1);
    }

    public function addDays(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} day");
    }

    public function addDay(): self
    {
        return $this->modify('1 day');
    }

    public function subDays(int $value): self
    {
        $value = (int) $value;

        return $this->modify("-{$value} day");
    }

    public function subDay(): self
    {
        return $this->modify('-1 day');
    }

    public function addWeekdays(int $value): self
    {
        return $this->modify((int) $value . ' weekdays ' . $this->format('H:i:s'));
    }

    public function addWeekday(): self
    {
        return $this->addWeekdays(1);
    }

    public function subWeekdays(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} weekdays ago, " . $this->format('H:i:s'));
    }

    public function subWeekday(): self
    {
        return $this->subWeekdays(1);
    }

    public function addWeeks(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} week");
    }

    public function addWeek(): self
    {
        return $this->modify('1 week');
    }

    public function subWeeks(int $value): self
    {
        $value = (int) $value;

        return $this->modify("-{$value} week");
    }

    public function subWeek(): self
    {
        return $this->modify('-1 week');
    }

    public function addHours(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} hour");
    }

    public function addHour(): self
    {
        return $this->modify('1 hour');
    }

    public function subHours(int $value): self
    {
        $value = (int) $value;

        return $this->modify("-{$value} hour");
    }

    public function subHour(): self
    {
        return $this->modify('-1 hour');
    }

    public function addMinutes(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} minute");
    }

    public function addMinute(): self
    {
        return $this->modify('1 minute');
    }

    public function subMinutes(int $value): self
    {
        $value = (int) $value;

        return $this->modify("-{$value} minute");
    }

    public function subMinute(): self
    {
        return $this->modify('-1 minute');
    }

    public function addSeconds(int $value): self
    {
        $value = (int) $value;

        return $this->modify("{$value} second");
    }

    public function addSecond(): self
    {
        return $this->modify('1 second');
    }

    public function subSeconds(int $value): self
    {
        $value = (int) $value;

        return $this->modify("-{$value} second");
    }

    public function subSecond(): self
    {
        return $this->modify('-1 second');
    }

    public function startOfDay(): self
    {
        return $this->modify('midnight');
    }

    public function endOfDay(): self
    {
        return $this->modify('23:59:59');
    }

    public function startOfMonth(): self
    {
        return $this->modify('first day of this month midnight');
    }

    public function endOfMonth(): self
    {
        return $this->modify('last day of this month, 23:59:59');
    }

    public function startOfYear(): self
    {
        return $this->modify('first day of january midnight');
    }

    public function endOfYear(): self
    {
        return $this->modify('last day of december, 23:59:59');
    }

    public function startOfDecade(): self
    {
        $year = $this->year()->toNative() - $this->year()->toNative() % DateConversion::YEARS_PER_DECADE;

        return $this->modify("first day of january {$year}, midnight");
    }

    public function endOfDecade(): self
    {
        $year = $this->year()->toNative() - $this->year()->toNative() %
            DateConversion::YEARS_PER_DECADE + DateConversion::YEARS_PER_DECADE - 1;

        return $this->modify("last day of december {$year}, 23:59:59");
    }

    public function startOfCentury(): self
    {
        $year = $this->startOfYear()
            ->setYear(
                ($this->year()->toNative() - 1) - ($this->year()->toNative() - 1) %
                DateConversion::YEARS_PER_CENTURY + 1
            )
            ->year()->toNative();

        return $this->modify("first day of january {$year}, midnight");
    }

    public function endOfCentury(): self
    {
        $year = $this->endOfYear()
            ->setYear(
                ($this->year()->toNative() - 1) - ($this->year()->toNative() - 1) %
                DateConversion::YEARS_PER_CENTURY + DateConversion::YEARS_PER_CENTURY
            )
            ->year()->toNative();

        return $this->modify("last day of december {$year}, 23:59:59");
    }

    public function startOfWeek(): self
    {
        $datetime = $this;
        if (! $datetime->dayOfWeek()->equals(static::weekStartsAt())) {
            $datetime = $datetime->previous(static::weekStartsAt());
        }

        return $datetime->startOfDay();
    }

    public function endOfWeek(): self
    {
        $datetime = $this;
        if (! $datetime->dayOfWeek()->equals(static::weekEndsAt())) {
            $datetime = $datetime->next(static::weekEndsAt());
        }

        return $datetime->endOfDay();
    }

    public function next(WeekDay $dayOfWeek = null): self
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek();
        }

        $day = $dayOfWeek->toNative();

        return $this->modify("next {$day}, midnight");
    }

    public function previous(WeekDay $dayOfWeek = null): self
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->dayOfWeek();
        }

        $day = $dayOfWeek->toNative();

        return $this->modify("last {$day}, midnight");
    }

    public function firstOfMonth(WeekDay $dayOfWeek = null): self
    {
        $day = $dayOfWeek === null ? 'day' : $dayOfWeek->toNative();

        return $this->modify("first {$day} of this month, midnight");
    }

    public function lastOfMonth(WeekDay $dayOfWeek = null): self
    {
        $day = $dayOfWeek === null ? 'day' : $dayOfWeek->toNative();

        return $this->modify("last {$day} of this month, midnight");
    }

    public function nthOfMonth(int $nth, WeekDay $dayOfWeek)
    {
        $datetime = $this->copy()->firstOfMonth();
        $check = $datetime->format('Y-m');
        $datetime = $datetime->modify("+{$nth} " . $dayOfWeek->toNative());

        return ($datetime->format('Y-m') === $check) ? $datetime : false;
    }

    public function firstOfQuarter(WeekDay $dayOfWeek = null): self
    {
        return $this->setDay(1)
            ->setMonth($this->quarter()->toNative() * DateConversion::MONTHS_PER_QUARTER - 2)
            ->firstOfMonth($dayOfWeek);
    }

    public function lastOfQuarter(WeekDay $dayOfWeek = null): self
    {
        return $this->setDay(1)
            ->setMonth($this->quarter()->toNative() * DateConversion::MONTHS_PER_QUARTER)
            ->lastOfMonth($dayOfWeek);
    }

    public function nthOfQuarter(int $nth, WeekDay $dayOfWeek)
    {
        $datetime = $this->copy()
            ->setDay(1)
            ->setMonth($this->quarter()->toNative() * DateConversion::MONTHS_PER_QUARTER);

        $lastMonth = $datetime->month()->toNative();
        $year = $datetime->year()->toNative();
        $datetime = $datetime->firstOfQuarter()->modify("+{$nth}" . $dayOfWeek->toNative());

        return ($lastMonth < $datetime->month()->toNative() || $year !== $datetime->year()->toNative()) ?
            false :
            $datetime;
    }

    public function firstOfYear(WeekDay $dayOfWeek = null): self
    {
        $day = $dayOfWeek === null ? 'day' : $dayOfWeek->toNative();

        return $this->modify("first {$day} of january, midnight");
    }

    public function lastOfYear(WeekDay $dayOfWeek = null): self
    {
        $day = $dayOfWeek === null ? 'day' : $dayOfWeek->toNative();

        return $this->modify("last {$day} of december, midnight");
    }

    public function nthOfYear(int $nth, WeekDay $dayOfWeek)
    {
        $datetime = $this->copy()->firstOfYear()->modify("+{$nth} " . $dayOfWeek->toNative());

        return $this->year()->equals($datetime->year()) ? $datetime : false;
    }

    public function average(DateTimeInterface $datetime = null): self
    {
        $datetime = ($datetime === null) ? static::now($this->timezoneName()->toNative()) : $datetime;

        return $this->addSeconds((int) ($this->diffInSeconds($datetime, false)->toNative() / 2));
    }
}
