<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Number;

use Commanded\Core\Assert\Assert;

/**
 * @method static static fromNative(float $value)
 * @method float toNative()
 */
class Real extends Number
{
    public function isZero(): bool
    {
        return ((float) $this->toNative()) == 0;
    }

    public function isInfinite(): bool
    {
        return \is_infinite((float) $this->toNative());
    }

    public function isPositive(): bool
    {
        return ((float) $this->toNative()) > 0;
    }

    public function isNegative(): bool
    {
        return ((float) $this->toNative()) < 0;
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

    public function intdiv(self $x): Integer
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new Integer(
            \intdiv($this->toNative() / $x->toNative())
        );
    }

    public function div(self $x): self
    {
        $value = $this->divSpecialCases($x);
        if ($value !== null) {
            return $value;
        }

        return new static($this->toNative() / $x->toNative());
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

    public function pow(self $x): self
    {
        return new static(\pow($this->toNative(), $x->toNative()));
    }

    public function sqrt($scale = null): self
    {
        if ($scale === null) {
            return new static(\sqrt($this->toNative()));
        }

        return new static(\bcsqrt($this->toNative(), $scale));
    }

    public function toInteger(RoundingMode $roundingMode = null): Integer
    {
        if ($roundingMode === null) {
            $roundingMode = RoundingMode::HALF_UP();
        }

        return Integer::fromNative(
            \round($this->toNative(), 0, $roundingMode->toNative())
        );
    }

    protected function init($value): void
    {
        Assert::float($value);
    }
}
