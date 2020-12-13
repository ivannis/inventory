<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Number\Integer;

/**
 * @method static static fromNative(int $value)
 */
class Second extends Integer
{
    const MIN_SECOND = 0;
    const MAX_SECOND = 59;

    public static function now(string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        return static::fromString('now', $timezone);
    }

    public static function fromString(string $time, string $timezone = DateTimeInterface::DEFAULT_TIMEZONE): self
    {
        $datetime = DateTime::fromString($time, $timezone);

        return new self(\intval($datetime->format('s')));
    }

    protected function init($value): void
    {
        parent::init($value);

        Assert::range($value, self::MIN_SECOND, self::MAX_SECOND);
    }
}
