<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static IpVersion IPV4()
 * @method static IpVersion IPv6()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class IpVersion extends Enum
{
    private const IPV4 = 'IPv4';
    private const IPV6 = 'IPv6';
}
