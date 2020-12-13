<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Climate;

class Fahrenheit extends Temperature
{
    public function __toString(): string
    {
        return \sprintf('%d °F', $this->toNative());
    }

    public function toCelsius(): Celsius
    {
        return Celsius::fromNative(($this->toNative() - 32) / 1.8);
    }

    public function toKelvin(): Kelvin
    {
        return Kelvin::fromNative($this->toCelsius()->toNative() + 273.15);
    }

    public function toFahrenheit(): Fahrenheit
    {
        return self::fromNative($this->toNative());
    }
}
