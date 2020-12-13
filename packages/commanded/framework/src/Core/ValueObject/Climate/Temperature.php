<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Climate;

use Commanded\Core\ValueObject\Number\Real;

/**
 * @method static static fromNative(float $value)
 */
abstract class Temperature extends Real
{
    abstract public function toCelsius(): Celsius;

    abstract public function toKelvin(): Kelvin;

    abstract public function toFahrenheit(): Fahrenheit;
}
