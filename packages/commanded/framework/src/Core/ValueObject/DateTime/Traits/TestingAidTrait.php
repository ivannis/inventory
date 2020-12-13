<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use Commanded\Core\ValueObject\DateTime\DateTimeInterface;

trait TestingAidTrait
{
    /**
     * A tests DateTimeInterface instance to be returned when now instances are created.
     *
     * There is a single tests now for all date/time classes.
     * This aims to emulate stubbing out 'now' which is a single global fact.
     *
     * @var DateTimeInterface
     */
    protected static $testNow;

    /**
     * Set a DateTimeInterface instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. DateTimeInterface::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Chronos(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Chronos('now')
     *   - When a string containing the desired time is passed to ChronosInterface::parse().
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the tests instance call this method using the default
     * parameter of null.
     *
     * @param null|mixed $testNow
     */
    public static function setTestNow($testNow = null): void
    {
        static::$testNow = $testNow instanceof DateTimeInterface ? $testNow : static::fromString($testNow);
    }

    /**
     * Get the DateTimeInterface instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return static
     */
    public static function getTestNow(): self
    {
        return static::$testNow;
    }

    /**
     * Determine if there is a valid tests instance set. A valid tests instance
     * is anything that is not null.
     */
    public static function hasTestNow(): bool
    {
        return static::$testNow !== null;
    }
}
