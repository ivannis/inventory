<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Identity;

use Commanded\Core\Assert\Assert;
use Ramsey\Uuid\Uuid as BaseUuid;

/**
 * @method static static fromNative(string $value)
 */
class Uuid extends StringId
{
    public static function next(): self
    {
        return new static(
            BaseUuid::uuid4()->toString()
        );
    }

    protected function init($value): void
    {
        Assert::uuid($value);
    }
}
