<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static DateConversion YEARS_PER_CENTURY()
 * @method static DateConversion YEARS_PER_DECADE()
 * @method static DateConversion MONTHS_PER_YEAR()
 * @method static DateConversion MONTHS_PER_QUARTER()
 * @method static DateConversion WEEKS_PER_YEAR()
 * @method static DateConversion DAYS_PER_WEEK()
 * @method static DateConversion HOURS_PER_DAY()
 * @method static DateConversion MINUTES_PER_HOUR()
 * @method static DateConversion SECONDS_PER_MINUTE()
 * @method static static fromNative(int $value)
 * @method int toNative()
 */
final class DateConversion extends Enum
{
    const YEARS_PER_CENTURY = 100;
    const YEARS_PER_DECADE = 10;
    const MONTHS_PER_YEAR = 12;
    const MONTHS_PER_QUARTER = 3;
    const WEEKS_PER_YEAR = 52;
    const DAYS_PER_WEEK = 7;
    const HOURS_PER_DAY = 24;
    const MINUTES_PER_HOUR = 60;
    const SECONDS_PER_MINUTE = 60;
}
