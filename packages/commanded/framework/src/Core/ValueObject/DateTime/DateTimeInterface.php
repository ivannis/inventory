<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use DateTimeImmutable;
use Commanded\Core\ValueObject\DateTime\Contract\Comparable;
use Commanded\Core\ValueObject\DateTime\Contract\Differentiable;
use Commanded\Core\ValueObject\DateTime\Contract\Formattable;
use Commanded\Core\ValueObject\DateTime\Contract\Instantiable;
use Commanded\Core\ValueObject\DateTime\Contract\Modificable;
use Commanded\Core\ValueObject\ValueObject;

/**
 * DateTime Interface.
 *
 * @method DateTimeImmutable toNative()
 */
interface DateTimeInterface extends ValueObject, Comparable, Differentiable, Instantiable, Formattable, Modificable
{
    const UTC_TIMEZONE = 'UTC';
    const DEFAULT_TIMEZONE = self::UTC_TIMEZONE;
}
