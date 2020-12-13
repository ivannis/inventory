<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Number;

use DomainException;
use Commanded\Core\ValueObject\AbstractValueObject;

abstract class Number extends AbstractValueObject
{
    protected function divSpecialCases(self $x): ?self
    {
        if ($x->isZero()) {
            throw new DomainException('Division by zero is not allowed.');
        }

        if (($this->isZero() || $x->isInfinite()) && ! $this->isInfinite()) {
            return new static(0);
        }

        return null;
    }
}
