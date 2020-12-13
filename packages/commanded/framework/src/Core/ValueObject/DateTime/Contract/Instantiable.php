<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Contract;

use Commanded\Core\ValueObject\DateTime\DateTimeInterface as DateTime;

interface Instantiable
{
    /**
     * Get a copy of the instance.
     */
    public function copy(): self;

    /**
     * Create a instance from a string.  This is an alias for the
     * constructor that allows better fluent syntax as it allows you to do
     * DateTime::parse('Monday next week')->fn() rather than
     * (new Chronos('Monday next week'))->fn().
     */
    public static function fromString(string $time = 'now', string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Get a instance for the current date and time.
     */
    public static function now(string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Create a instance for today.
     */
    public static function today(string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Create a instance for tomorrow.
     */
    public static function tomorrow(string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Create a instance for yesterday.
     */
    public static function yesterday(string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Create a instance for the greatest supported date.
     */
    public static function maxValue(): self;

    /**
     * Create a instance for the lowest supported date.
     */
    public static function minValue(): self;

    /**
     * Create a new instance from a specific date and time.
     *
     * If any of $year, $month or $day are set to null their now() values
     * will be used.
     *
     * If $hour is null it will be set to its now() value and the default values
     * for $minute and $second will be their now() values.
     * If $hour is not null then the default values for $minute and $second
     * will be 0.
     */
    public static function create(
        int $year = null,
        int $month = null,
        int $day = null,
        int $hour = null,
        int $minute = null,
        int $second = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self;

    /**
     * Create a instance from just a date. The time portion is set to now.
     */
    public static function fromDate(
        int $year = null,
        int $month = null,
        int $day = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self;

    /**
     * Create a instance from just a time. The date portion is set to today.
     */
    public static function fromTime(
        int $hour = null,
        int $minute = null,
        int $second = null,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self;

    /**
     * Create a instance from a specific format.
     *
     * @throws \InvalidArgumentException
     */
    public static function fromFormat(
        string $format,
        string $time,
        string $timezone = DateTime::DEFAULT_TIMEZONE
    ): self;

    /**
     * Create a instance from a timestamp.
     */
    public static function fromTimestamp(int $timestamp, string $timezone = DateTime::DEFAULT_TIMEZONE): self;

    /**
     * Create a instance from an UTC timestamp.
     */
    public static function fromTimestampUTC(int $timestamp): self;

    /**
     * Returns any errors or warnings that were found during the parsing
     * of the last object created by this class.
     */
    public static function lastErrors(): array;
}
