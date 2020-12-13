<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method static static fromNative(int $value)
 */
class Minute extends Integer
{
    const MIN_MINUTE = 0;
    const MAX_MINUTE = 59;

    public static function now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return static::fromString('now', $timezone);
    }

    public static function fromString(string $time, string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        $datetime = DateTime::fromString($time, $timezone);

        return new self(\intval($datetime->format('i')));
    }

    protected function init($value): void
    {
        parent::init($value);

        Assert::range($value, self::MIN_MINUTE, self::MAX_MINUTE);
    }
}
