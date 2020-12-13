<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

use DateTime;
use DateTimeImmutable;
use Commanded\Core\ValueObject\DateTime\DateTimeInterface;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method DateTimeImmutable toNative()
 * @method DateTimeInterface withUtc()
 */
trait FormattingTrait
{
    public function __toString(): string
    {
        return $this->format(static::$toStringFormat);
    }

    public static function resetToStringFormat(): void
    {
        static::setToStringFormat(DateTimeInterface::DEFAULT_TO_STRING_FORMAT);
    }

    public static function setToStringFormat(string $format): void
    {
        static::$toStringFormat = $format;
    }

    public function format(string $format): string
    {
        return $this->toNative()->format($format);
    }

    public function toDateString(): string
    {
        return $this->format('Y-m-d');
    }

    public function toFormattedDateString(): string
    {
        return $this->format('M j, Y');
    }

    public function toTimeString(): string
    {
        return $this->format('H:i:s');
    }

    public function toDateTimeString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toDayDateTimeString(): string
    {
        return $this->format('D, M j, Y g:i A');
    }

    public function toAtomString(): string
    {
        return $this->format(DateTime::ATOM);
    }

    public function toCookieString(): string
    {
        return $this->format(DateTime::COOKIE);
    }

    public function toIso8601String(): string
    {
        return $this->format(DateTime::ATOM);
    }

    public function toRfc822String(): string
    {
        return $this->format(DateTime::RFC822);
    }

    public function toRfc850String(): string
    {
        return $this->format(DateTime::RFC850);
    }

    public function toRfc1036String(): string
    {
        return $this->format(DateTime::RFC1036);
    }

    public function toRfc1123String(): string
    {
        return $this->format(DateTime::RFC1123);
    }

    public function toRfc2822String(): string
    {
        return $this->format(DateTime::RFC2822);
    }

    public function toRfc3339String(): string
    {
        return $this->format(DateTime::RFC3339);
    }

    public function toRssString(): string
    {
        return $this->format(DateTime::RSS);
    }

    public function toW3cString(): string
    {
        return $this->format(DateTime::W3C);
    }

    public function toUnixString(): string
    {
        return $this->format('U');
    }

    public function toQuarter(): Integer
    {
        return Integer::fromNative((int) ceil((int) ($this->format('m')) / 3));
    }

    public function toQuarterRange(): array
    {
        $quarter = $this->toQuarter()->toNative();
        $year = (int) $this->format('Y');

        switch ($quarter) {
            case 1:
                return [$year . '-01-01', $year . '-03-31'];
            case 2:
                return [$year . '-04-01', $year . '-06-30'];
            case 3:
                return [$year . '-07-01', $year . '-09-30'];
            case 4:
                return [$year . '-10-01', $year . '-12-31'];
        }
    }

    public function toWeek(): Integer
    {
        return Integer::fromNative((int) $this->format('W'));
    }

    public function jsonSerialize()
    {
        return $this->withUTC()->__toString();
    }
}
