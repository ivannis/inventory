<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Contract;

use Commanded\Core\ValueObject\DateTime\DateInterval;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface;

interface Differentiable
{
    /**
     * Get the difference in years.
     */
    public function diffInYears(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in months.
     */
    public function diffInMonths(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in weeks.
     */
    public function diffInWeeks(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in days.
     */
    public function diffInDays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in days using a filter callable.
     */
    public function diffInDaysFiltered(
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in hours using a filter callable.
     */
    public function diffInHoursFiltered(
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference by the given interval using a filter callable.
     */
    public function diffFiltered(
        DateInterval $interval,
        callable $callback,
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in weekdays.
     */
    public function diffInWeekdays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in weekend days using a filter.
     */
    public function diffInWeekendDays(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in hours.
     */
    public function diffInHours(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in minutes.
     */
    public function diffInMinutes(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Get the difference in seconds.
     */
    public function diffInSeconds(
        DateTimeInterface $datetime = null,
        bool $absolute = true
    ): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * The number of seconds since midnight.
     */
    public function secondsSinceMidnight(): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * The number of seconds until 23:59:59.
     */
    public function secondsUntilEndOfDay(): \Commanded\Core\ValueObject\Number\Integer;

    /**
     * Convenience method for getting the remaining time from a given time.
     */
    public static function fromNow(DateTimeInterface $datetime): DateInterval;
}
