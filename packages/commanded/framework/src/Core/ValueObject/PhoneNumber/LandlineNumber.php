<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\PhoneNumber;

use Commanded\Core\Assert\Assert;

class LandlineNumber extends PhoneNumber
{
    protected function init($value): void
    {
        Assert::true(
            $this->numberType->equals(PhoneNumberType::LANDLINE()) ||
            $this->numberType->equals(PhoneNumberType::LANDLINE_OR_MOBILE()),
            \sprintf(
                'Value "%s" expected to be a valid "%s" landline number.',
                $this->__toString(),
                $this->countryCode
            )
        );
    }
}
