<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject;

interface ValueObject extends \JsonSerializable
{
    /**
     * Returns a string representation of the value object.
     */
    public function __toString(): string;

    /**
     * @param mixed $value
     */
    public static function fromNative($value): ValueObject;

    /**
     * @return mixed
     */
    public function toNative();

    /**
     * Tells whether two value object are equal by comparing their values.
     */
    public function equals(ValueObject $other): bool;
}
