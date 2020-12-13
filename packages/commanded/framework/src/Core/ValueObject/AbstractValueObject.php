<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject;

abstract class AbstractValueObject implements ValueObject
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->init($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function fromNative($value): ValueObject
    {
        return new static($value);
    }

    public function toNative()
    {
        return $this->value;
    }

    public function equals(ValueObject $other): bool
    {
        return Util::classEquals($this, $other) && $this->toNative() === $other->toNative();
    }

    public function jsonSerialize()
    {
        return $this->toNative();
    }

    abstract protected function init($value): void;
}
