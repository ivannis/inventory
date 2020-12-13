<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject;

use Commanded\Core\ValueObject\Intl\LanguageCode;

class Util
{
    /**
     * Tells whether two objects are of the same class.
     *
     * @param mixed $objectA
     * @param mixed $objectB
     */
    public static function classEquals($objectA, $objectB): bool
    {
        return \get_class($objectA) === \get_class($objectB);
    }

    public static function primaryLanguage(string $locale): LanguageCode
    {
        return LanguageCode::fromNative(\Locale::getPrimaryLanguage($locale));
    }
}
