<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(string $value)
 */
class Ip extends Domain
{
    private IpVersion $version;

    public function version(): IpVersion
    {
        return $this->version;
    }

    protected function init($value): void
    {
        Assert::ip($value);

        $this->version = filter_var($value, FILTER_FLAG_IPV4) !== false ?
            IpVersion::IPV4() :
            IpVersion::IPV6();
    }
}
