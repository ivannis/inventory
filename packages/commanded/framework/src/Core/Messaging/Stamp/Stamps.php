<?php

declare(strict_types=1);

namespace Commanded\Core\Messaging\Stamp;

final class Stamps
{
    public static function named(string $name): NamedStamp
    {
        return new NamedStamp($name);
    }
}
