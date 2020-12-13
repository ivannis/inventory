<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Contract;

use Commanded\Core\ValueObject\DateTime\DateTimeInterface;

interface Comparable
{
    /**
     * Get weekend days.
     *
     * @raturn array|WeekDay[]
     */
    public static function weekendDays(): array;

    /**
     * Determines if the instance is not equal to another.
     */
    public function notEquals(DateTimeInterface $other): bool;

    /**
     * Determines if the instance is greater (after) than another.
     */
    public function greaterThan(DateTimeInterface $other): bool;

    /**
     * Determines if the instance is greater (after) than or equal to another.
     */
    public function greaterThanOrEqual(DateTimeInterface $other): bool;

    /**
     * Determines if the instance is less (before) than another.
     */
    public function lessThan(DateTimeInterface $other): bool;

    /**
     * Determines if the instance is less (before) or equal to another.
     */
    public function lessThanOrEqual(DateTimeInterface $other): bool;

    /**
     * Determines if the instance is between two others.
     */
    public function between(DateTimeInterface $other1, DateTimeInterface $other2, bool $equal = true): bool;

    /**
     * Get the closest date from the instance.
     *
     * @return statis
     */
    public function closest(DateTimeInterface $other1, DateTimeInterface $other2);

    /**
     * Get the farthest date from the instance.
     *
     * @return statis
     */
    public function farthest(DateTimeInterface $other1, DateTimeInterface $other2);

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @return statis
     */
    public function min(DateTimeInterface $other = null);

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @return statis
     */
    public function max(DateTimeInterface $other = null);

    /**
     * Determines if the instance is a weekday.
     */
    public function isWeekday(): bool;

    /**
     * Determines if the instance is a weekend day.
     */
    public function isWeekend(): bool;

    /**
     * Determines if the instance is yesterday.
     */
    public function isYesterday(): bool;

    /**
     * Determines if the instance is today.
     */
    public function isToday(): bool;

    /**
     * Determines if the instance is tomorrow.
     */
    public function isTomorrow(): bool;

    /**
     * Determines if the instance is in the future, ie. greater (after) than now.
     */
    public function isFuture(): bool;

    /**
     * Determines if the instance is in the past, ie. less (before) than now.
     */
    public function isPast(): bool;

    /**
     * Determines if the instance is a leap year.
     */
    public function isLeapYear(): bool;

    /**
     * Checks if the passed in date is the same day as the instance current day.
     */
    public function isSameDay(DateTimeInterface $other): bool;

    /**
     * Checks if this day is a Sunday.
     */
    public function isSunday(): bool;

    /**
     * Checks if this day is a Monday.
     */
    public function isMonday(): bool;

    /**
     * Checks if this day is a Tuesday.
     */
    public function isTuesday(): bool;

    /**
     * Checks if this day is a Wednesday.
     */
    public function isWednesday(): bool;

    /**
     * Checks if this day is a Thursday.
     */
    public function isThursday(): bool;

    /**
     * Checks if this day is a Friday.
     */
    public function isFriday(): bool;

    /**
     * Checks if this day is a Saturday.
     */
    public function isSaturday(): bool;

    /**
     * Returns true if this object represents a date within the current week.
     */
    public function isThisWeek(): bool;

    /**
     * Returns true if this object represents a date within the current month.
     */
    public function isThisMonth(): bool;

    /**
     * Returns true if this object represents a date within the current year.
     */
    public function isThisYear(): bool;

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     */
    public function isBirthday(DateTimeInterface $other): bool;

    /**
     * Returns true this instance happened within the specified interval.
     *
     * @param string $timeInterval the numeric value with space then time type.
     *                             Example of valid types: 6 hours, 2 days, 1 minute.
     */
    public function wasWithinLast(string $timeInterval): bool;

    /**
     * Returns true this instance will happen within the specified interval.
     *
     * @param string $timeInterval the numeric value with space then time type.
     *                             Example of valid types: 6 hours, 2 days, 1 minute.
     */
    public function isWithinNext(string $timeInterval): bool;
}
