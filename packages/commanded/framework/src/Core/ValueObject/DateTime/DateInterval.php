<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\ValueObject\Number\Integer;
use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static DateInterval fromYears(int $years = 1)
 * @method static DateInterval fromMonths(int $months = 1)
 * @method static DateInterval fromWeeks(int $weeks = 1)
 * @method static DateInterval fromDays(int $days = 1)
 * @method static DateInterval fromHours(int $hours = 1)
 * @method static DateInterval fromMinutes(int $minutes = 1)
 * @method static DateInterval fromSeconds(int $seconds = 1)
 */
class DateInterval extends StringLiteral
{
    /**
     * Interval spec period designators.
     */
    const PERIOD_PREFIX = 'P';
    const PERIOD_YEARS = 'Y';
    const PERIOD_MONTHS = 'M';
    const PERIOD_DAYS = 'D';
    const PERIOD_TIME_PREFIX = 'T';
    const PERIOD_HOURS = 'H';
    const PERIOD_MINUTES = 'M';
    const PERIOD_SECONDS = 'S';

    private Integer $years, $months, $days, $weeks, $hours, $minutes, $seconds, $micro;
    private bool $invert;

    /**
     * Provide static helpers to create instances. Allows:.
     *
     * ```
     * DateInterval::fromYears(3)
     * // or
     * DateInterval::fromMonths(1);
     * ```
     *
     * Note: This is done using the magic method to allow static and instance methods to
     *       have the same names.
     *
     * @param string $name The property to configure. Accepts singular and plural forms.
     * @param array $args contains the value to use
     * @return static
     */
    public static function __callStatic($name, $args)
    {
        $arg = count($args) === 0 ? 1 : $args[0];

        switch ($name) {
            case 'fromYears':
                return static::fromNative(sprintf('P%sY', $arg));
            case 'fromMonths':
                return static::fromNative(sprintf('P%sM', $arg));
            case 'fromWeeks':
                $days = (int) ($arg * DateConversion::DAYS_PER_WEEK);
                return static::fromNative(sprintf('P%sD', $days));
            case 'fromDays':
                return static::fromNative(sprintf('P%sD', $arg));
            case 'fromHours':
                return static::fromNative(sprintf('PT%sH', $arg));
            case 'fromMinutes':
                return static::fromNative(sprintf('PT%sM', $arg));
            case 'fromSeconds':
                return static::fromNative(sprintf('PT%sS', $arg));
        }
    }

    public function years(): Integer
    {
        return $this->years;
    }

    public function months(): Integer
    {
        return $this->months;
    }

    public function weeks(): Integer
    {
        return $this->weeks;
    }

    public function days(): Integer
    {
        return $this->days;
    }

    public function hours(): Integer
    {
        return $this->hours;
    }

    public function minutes(): Integer
    {
        return $this->minutes;
    }

    public function seconds(): Integer
    {
        return $this->seconds;
    }

    public function microseconds(): Integer
    {
        return $this->micro;
    }

    public function isNegative(): bool
    {
        return $this->invert;
    }

    public function format(string $format): string
    {
        return $this->toDateInterval()->format($format);
    }

    public static function fromDateInterval(\DateInterval $interval): self
    {
        return static::fromNative(
            static::intervalToSpecString($interval)
        );
    }

    public static function fromDateString(string $time): self
    {
        return static::fromNative(
            static::intervalToSpecString(\DateInterval::createFromDateString($time))
        );
    }

    public function toDateInterval(): \DateInterval
    {
        return new \DateInterval($this->toNative());
    }

    protected function init($value): void
    {
        parent::init($value);

        $interval = new \DateInterval($value);

        $this->years = Integer::fromNative((int) $interval->y);
        $this->months = Integer::fromNative((int) $interval->m);
        $this->weeks = Integer::fromNative((int) floor($interval->d / DateConversion::DAYS_PER_WEEK));
        $this->days = Integer::fromNative((int) $interval->d);
        $this->hours = Integer::fromNative((int) $interval->h);
        $this->minutes = Integer::fromNative((int) $interval->i);
        $this->seconds = Integer::fromNative((int) $interval->s);
        $this->micro = Integer::fromNative((int) $interval->f);
        $this->invert = (bool) $interval->invert;
    }

    private static function intervalToSpecString(\DateInterval $interval): string
    {
        $years = (int) $interval->y;
        $months = (int) $interval->m;
        $weeks = (int) floor($interval->d / DateConversion::DAYS_PER_WEEK);
        $days = (int) $interval->d;
        $hours = (int) $interval->h;
        $minutes = (int) $interval->m;
        $seconds = (int) $interval->s;

        $spec = static::PERIOD_PREFIX;
        $spec .= $years > 0 ? $years . static::PERIOD_YEARS : '';
        $spec .= $months > 0 ? $months . static::PERIOD_MONTHS : '';

        $specDays = 0;
        $specDays += $weeks > 0 ? $weeks * DateConversion::DAYS_PER_WEEK : 0;
        $specDays += $days > 0 ? $days : 0;

        $spec .= ($specDays > 0) ? $specDays . static::PERIOD_DAYS : '';

        if ($spec === static::PERIOD_PREFIX) {
            $spec .= '0' . static::PERIOD_YEARS;
        }

        if ($hours > 0 || $minutes > 0 || $seconds > 0) {
            $spec .= static::PERIOD_TIME_PREFIX;
            $spec .= $hours > 0 ? $hours . static::PERIOD_HOURS : '';
            $spec .= $minutes > 0 ? $minutes . static::PERIOD_MINUTES : '';
            $spec .= $seconds > 0 ? $seconds . static::PERIOD_SECONDS : '';
        }

        return $spec;
    }
}
