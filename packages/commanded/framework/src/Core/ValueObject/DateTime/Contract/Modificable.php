<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Contract;

use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\DateTime\WeekDay;

interface Modificable
{
    /**
     * Get the first day of week.
     */
    public static function weekStartsAt(): WeekDay;

    /**
     * Get the last day of week.
     */
    public static function weekEndsAt(): WeekDay;

    /**
     * Alters the timestamp and return a new date instance.
     */
    public function modify(string $relative): self;

    /**
     * Set the date to a different date.
     *
     * Workaround for a PHP bug related to the first day of a month
     * @see https://bugs.php.net/bug.php?id=63863
     */
    public function setDate(int $year, int $month, int $day): self;

    /**
     * Set the date and time all together.
     */
    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0): self;

    /**
     * Set the date and time all together.
     */
    public function setTime(int $hour, int $minute, int $second = 0): self;

    /**
     * Set the time by time string.
     */
    public function setTimeFromTimeString(string $time): self;

    /**
     * Set the timezone by time string.
     */
    public function withTimezone(string $timezone): self;

    /**
     * Set the UTC timezone.
     */
    public function withUTC(): self;

    /**
     * Set the instance's timestamp.
     */
    public function setTimestamp(int $value): self;

    /**
     * Set the instance's year.
     */
    public function setYear(int $value): self;

    /**
     * Set the instance's month.
     */
    public function setMonth(int $value): self;

    /**
     * Set the instance's day.
     */
    public function setDay(int $value): self;

    /**
     * Set the instance's hour.
     */
    public function setHour(int $value): self;

    /**
     * Set the instance's minute.
     */
    public function setMinute(int $value): self;

    /**
     * Set the instance's second.
     */
    public function setSecond(int $value): self;

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     */
    public function addYears(int $value): self;

    /**
     * Add a year to the instance.
     */
    public function addYear(): self;

    /**
     * Remove years from the instance.
     */
    public function subYears(int $value): self;

    /**
     * Remove a year from the instance.
     */
    public function subYear(): self;

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new DateTime('2015-01-03'))->addMonths(1); // Results in 2015-02-03
     *
     *  (new DateTime('2015-01-31'))->addMonths(1); // Results in 2015-02-28
     * ```
     */
    public function addMonths(int $value): self;

    /**
     * Add a month to the instance.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new DateTime('2015-01-03'))->addMonth(); // Results in 2015-02-03
     *
     *  (new DateTime('2015-01-31'))->addMonth(); // Results in 2015-02-28
     * ```
     */
    public function addMonth(): self;

    /**
     * Remove months from the instance.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new DateTime('2015-03-01'))->subMonths(1); // Results in 2015-02-01
     *
     *  (new DateTime('2015-03-31'))->subMonths(1); // Results in 2015-02-28
     * ```
     */
    public function subMonths(int $value): self;

    /**
     * Remove a month from the instance.
     *
     * When adding or subtracting months, if the resulting time is a date
     * that does not exist, the result of this operation will always be the
     * last day of the intended month.
     *
     * ### Example:
     *
     * ```
     *  (new DateTime('2015-03-01'))->subMonth(); // Results in 2015-02-01
     *
     *  (new DateTime('2015-03-31'))->subMonth(); // Results in 2015-02-28
     * ```
     */
    public function subMonth(): self;

    /**
     * Add months with overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     */
    public function addMonthsWithOverflow(int $value): self;

    /**
     * Add a month with overflow to the instance.
     */
    public function addMonthWithOverflow(): self;

    /**
     * Remove months with overflow from the instance.
     */
    public function subMonthsWithOverflow(int $value): self;

    /**
     * Remove a month with overflow from the instance.
     */
    public function subMonthWithOverflow(): self;

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addDays(int $value): self;

    /**
     * Add a day to the instance.
     */
    public function addDay(): self;

    /**
     * Remove days from the instance.
     */
    public function subDays(int $value): self;

    /**
     * Remove a day from the instance.
     */
    public function subDay(): self;

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addWeekdays(int $value): self;

    /**
     * Add a weekday to the instance.
     */
    public function addWeekday(): self;

    /**
     * Remove weekdays from the instance.
     */
    public function subWeekdays(int $value): self;

    /**
     * Remove a weekday from the instance.
     */
    public function subWeekday(): self;

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addWeeks(int $value): self;

    /**
     * Add a week to the instance.
     */
    public function addWeek(): self;

    /**
     * Remove weeks to the instance.
     */
    public function subWeeks(int $value): self;

    /**
     * Remove a week from the instance.
     */
    public function subWeek(): self;

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addHours(int $value): self;

    /**
     * Add an hour to the instance.
     */
    public function addHour(): self;

    /**
     * Remove hours from the instance.
     */
    public function subHours(int $value): self;

    /**
     * Remove an hour from the instance.
     */
    public function subHour(): self;

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addMinutes(int $value): self;

    /**
     * Add a minute to the instance.
     */
    public function addMinute(): self;

    /**
     * Remove minutes from the instance.
     */
    public function subMinutes(int $value): self;

    /**
     * Remove a minute from the instance.
     */
    public function subMinute(): self;

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     */
    public function addSeconds(int $value): self;

    /**
     * Add a second to the instance.
     */
    public function addSecond(): self;

    /**
     * Remove seconds from the instance.
     */
    public function subSeconds(int $value): self;

    /**
     * Remove a second from the instance.
     */
    public function subSecond(): self;

    /**
     * Resets the time to 00:00:00.
     */
    public function startOfDay(): self;

    /**
     * Resets the time to 23:59:59.
     */
    public function endOfDay(): self;

    /**
     * Resets the date to the first day of the month and the time to 00:00:00.
     */
    public function startOfMonth(): self;

    /**
     * Resets the date to end of the month and time to 23:59:59.
     */
    public function endOfMonth(): self;

    /**
     * Resets the date to the first day of the year and the time to 00:00:00.
     */
    public function startOfYear(): self;

    /**
     * Resets the date to end of the year and time to 23:59:59.
     */
    public function endOfYear(): self;

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00.
     */
    public function startOfDecade(): self;

    /**
     * Resets the date to end of the decade and time to 23:59:59.
     */
    public function endOfDecade(): self;

    /**
     * Resets the date to the first day of the century and the time to 00:00:00.
     */
    public function startOfCentury(): self;

    /**
     * Resets the date to end of the century and time to 23:59:59.
     */
    public function endOfCentury(): self;

    /**
     * Resets the date to the first day of week and the time to 00:00:00.
     */
    public function startOfWeek(): self;

    /**
     * Resets the date to end of week and time to 23:59:59.
     */
    public function endOfWeek(): self;

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function next(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function previous(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function firstOfMonth(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function lastOfMonth(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     *
     * @return bool|static
     */
    public function nthOfMonth(int $nth, WeekDay $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function firstOfQuarter(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function lastOfQuarter(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     *
     * @return bool|static
     */
    public function nthOfQuarter(int $nth, WeekDay $dayOfWeek);

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function firstOfYear(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     */
    public function lastOfYear(WeekDay $dayOfWeek = null): self;

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. WeekDay::MONDAY.
     *
     * @return bool|static
     */
    public function nthOfYear(int $nth, WeekDay $dayOfWeek);

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     */
    public function average(DateTimeInterface $datetime = null): self;
}
