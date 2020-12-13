<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\ValueObject\String\StringLiteral;

abstract class Domain extends StringLiteral
{
    public static function create($value): Domain
    {
        if (filter_var($value, FILTER_VALIDATE_IP) !== false) {
            return Ip::fromNative($value);
        }

        return Hostname::fromNative($value);
    }
}
