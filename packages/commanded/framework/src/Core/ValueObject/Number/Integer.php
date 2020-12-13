<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Number;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(int $value)
 * @method int toNative()
 */
class Integer extends Number
{
    public function isZero(): bool
    {
        return $this->toNative() === 0;
    }

    public function isInfinite(): bool
    {
        return false;
    }

    public function isPositive(): bool
    {
        return $this->toNative() > 0;
    }

    public function isNegative(): bool
    {
        return $this->toNative() < 0;
    }

    public function add(self $x): self
    {
        return new static($this->toNative() + $x->toNative());
    }

    public function sub(self $x): self
    {
        return new static($this->toNative() - $x->toNative());
    }

    public function mult(self $x): self
    {
        return new static($this->toNative() * $x->toNative());
    }

    public function intdiv(self $x): self
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new static(
            intdiv($this->toNative() / $x->toNative())
        );
    }

    public function div(self $x): Real
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new Real(
            \intdiv($this->toNative() / $x->toNative())
        );
    }

    public function inc(): self
    {
        return new static($this->toNative() + 1);
    }

    public function dec(): self
    {
        return new static($this->toNative() - 1);
    }

    public function mod(self $x): self
    {
        return new static($this->toNative() % $x->toNative());
    }

    public function isEven(): bool
    {
        return $this->mod(self::fromNative(2))->isZero();
    }

    public function isOdd(): bool
    {
        return ! $this->isEven();
    }

    public function toReal(): Real
    {
        return Real::fromNative($this->toNative());
    }

    protected function init($value): void
    {
        Assert::integer($value);
    }
}
