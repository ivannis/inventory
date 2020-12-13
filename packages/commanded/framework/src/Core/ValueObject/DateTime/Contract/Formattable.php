<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Contract;

interface Formattable
{
    /**
     * Default format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    const DEFAULT_TO_STRING_FORMAT = 'Y-m-d H:i:s';

    /**
     * Reset the format used to the default when type juggling a ChronosInterface instance to a string.
     */
    public static function resetToStringFormat(): void;

    /**
     * Set the default format used when type juggling a ChronosInterface instance to a string.
     */
    public static function setToStringFormat(string $format): void;

    /**
     * Returns date formatted according to given format.
     */
    public function format(string $format): string;

    /**
     * Format the instance as date.
     */
    public function toDateString(): string;

    /**
     * Format the instance as a readable date.
     */
    public function toFormattedDateString(): string;

    /**
     * Format the instance as time.
     */
    public function toTimeString(): string;

    /**
     * Format the instance as date and time.
     */
    public function toDateTimeString(): string;

    /**
     * Format the instance with day, date and time.
     */
    public function toDayDateTimeString(): string;

    /**
     * Format the instance as ATOM.
     */
    public function toAtomString(): string;

    /**
     * Format the instance as COOKIE.
     */
    public function toCookieString(): string;

    /**
     * Format the instance as ISO8601.
     */
    public function toIso8601String(): string;

    /**
     * Format the instance as RFC822.
     */
    public function toRfc822String(): string;

    /**
     * Format the instance as RFC850.
     */
    public function toRfc850String(): string;

    /**
     * Format the instance as RFC1036.
     */
    public function toRfc1036String(): string;

    /**
     * Format the instance as RFC1123.
     */
    public function toRfc1123String(): string;

    /**
     * Format the instance as RFC2822.
     */
    public function toRfc2822String(): string;

    /**
     * Format the instance as RFC3339.
     */
    public function toRfc3339String(): string;

    /**
     * Format the instance as RSS.
     */
    public function toRssString(): string;

    /**
     * Format the instance as W3C.
     */
    public function toW3cString(): string;

    /**
     * Returns a UNIX timestamp.
     */
    public function toUnixString(): string;

    /**
     * Returns the quarter.
     */
    public function toQuarter(): \Commanded\Core\ValueObject\Number\Integer;

    /**
     *  Returns the quarter's range.
     *
     * @return array|string[]
     */
    public function toQuarterRange(): array;

    public function toWeek(): \Commanded\Core\ValueObject\Number\Integer;
}
