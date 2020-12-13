<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Intl;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static LocalizableValueMode STRICT()
 * @method static LocalizableValueMode ANY()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class LocalizableValueMode extends Enum
{
    const __DEFAULT = self::ANY;

    private const STRICT = 'strict';
    private const ANY = 'any';
}
