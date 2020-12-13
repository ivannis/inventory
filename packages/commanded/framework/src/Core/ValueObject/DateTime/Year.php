<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\ValueObject\Number\Natural;

/**
 * @method static static fromNative(int $value)
 */
class Year extends Natural
{
    public static function now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return static::fromString('now', $timezone);
    }

    public static function fromString(string $time, string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        $datetime = DateTime::fromString($time, $timezone);

        return new self(\intval($datetime->format('Y')));
    }
}
