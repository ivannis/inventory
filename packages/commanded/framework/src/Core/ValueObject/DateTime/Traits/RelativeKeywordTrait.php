<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime\Traits;

trait RelativeKeywordTrait
{
    protected static $relativePattern = '/this|next|last|tomorrow|yesterday|midnight|today|[+-]|first|last|ago/i';

    /**
     * Determine if there is just a time in the time string.
     *
     * @param string $time the time string to check
     * @return bool true if there is a keyword, otherwise false
     */
    protected static function isTimeExpression($time)
    {
        // Just a time
        if (preg_match('/^[0-2]?[0-9]:[0-5][0-9](?::[0-5][0-9])?$/', $time)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if there is a relative keyword in the time string, this is to
     * create dates relative to now for tests instances. e.g.: next tuesday.
     *
     * @param string $time the time string to check
     * @return bool true if there is a keyword, otherwise false
     */
    protected static function hasRelativeKeywords($time)
    {
        if (self::isTimeExpression($time)) {
            return true;
        }

        // skip common format with a '-' in it
        if (preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1) {
            return preg_match(static::$relativePattern, $time) > 0;
        }

        return false;
    }
}
