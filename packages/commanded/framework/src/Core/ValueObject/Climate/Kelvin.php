<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Climate;

class Kelvin extends Temperature
{
    public function __toString(): string
    {
        return \sprintf('%d K', $this->toNative());
    }

    public function toCelsius(): Celsius
    {
        return Celsius::fromNative($this->toNative() - 273.15);
    }

    public function toKelvin(): Kelvin
    {
        return self::fromNative($this->toNative());
    }

    public function toFahrenheit(): Fahrenheit
    {
        return Fahrenheit::fromNative($this->toCelsius()->toNative() * 1.8 + 32);
    }
}
