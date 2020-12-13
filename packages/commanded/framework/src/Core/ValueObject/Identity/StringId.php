<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Identity;

use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static static fromNative(string $value)
 */
class StringId extends StringLiteral implements Id
{
}
