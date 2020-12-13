<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\Validator\Exception\InvalidArgumentException;
use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static static fromNative(string $value)
 */
class TimeZoneName extends StringLiteral
{
    public function __construct($value)
    {
        Assert::notEmpty($value);
        if (!in_array($value, timezone_identifiers_list())) {
            $value = self::offsetToName($value);
        }

        parent::__construct($value);
    }

    public static function fromDefault(): self
    {
        return new static(date_default_timezone_get());
    }

    public static function fromOffset(string $offset): self
    {
        return new static(static::offsetToName($offset));
    }

    protected function init($value): void
    {
        Assert::timezone($value);
    }

    private static function offsetToName(string $offset): ?string
    {
        if (strpos($offset, ':') === false) {
            throw new InvalidArgumentException(
                sprintf('The timezone offset "%s" is invalid.', $offset),
                Assert::INVALID_TIMEZONE
            );
        }

        // Calculate seconds from offset
        [$hours, $minutes] = explode(':', $offset);
        $seconds = $hours * 60 * 60 + $minutes * 60;

        // Get timezone name from seconds
        $timezone = timezone_name_from_abbr('', $seconds, 1);
        if ($timezone === false) {
            $timezone = timezone_name_from_abbr('', $seconds, 0);

            if ($timezone === false) {
                $timezone = timezone_name_from_abbr('UTC');
            }
        }

        return $timezone;
    }
}
