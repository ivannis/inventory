<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Climate;

class Celsius extends Temperature
{
    public function __toString(): string
    {
        return \sprintf('%d °C', $this->toNative());
    }

    public function toCelsius(): Celsius
    {
        return self::fromNative($this->toNative());
    }

    public function toKelvin(): Kelvin
    {
        return Kelvin::fromNative($this->toNative() + 273.15);
    }

    public function toFahrenheit(): Fahrenheit
    {
        return Fahrenheit::fromNative($this->toNative() * 1.8 + 32);
    }
}
