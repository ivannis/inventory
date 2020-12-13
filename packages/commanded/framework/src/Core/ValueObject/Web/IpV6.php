<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(string $value)
 */
class IpV6 extends Ip
{
    protected function init($value): void
    {
        parent::init($value);

        Assert::ipv6($value);
    }
}
