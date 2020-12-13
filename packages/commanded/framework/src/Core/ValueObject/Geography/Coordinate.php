<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class Coordinate extends AbstractValueObject
{
    private Latitude $latitude;
    private Longitude $longitude;

    public function __toString(): string
    {
        return \sprintf('[%F, %F]', $this->latitude->toNative(), $this->longitude->toNative());
    }

    public function latitude(): Latitude
    {
        return $this->latitude;
    }

    public function longitude(): Longitude
    {
        return $this->longitude;
    }

    public function distanceTo(Coordinate $coordinate, DistanceUnit $unit = null): Distance
    {
        return Distance::fromNative([
            'from' => $this->toNative(),
            'to' => $coordinate->toNative(),
            'unit' => $unit ?? $unit->toNative(),
        ]);
    }

    protected function init($value): void
    {
        Assert::isArray($value);
        Assert::keyExists($value, 'lat');
        Assert::keyExists($value, 'lng');

        $this->latitude = Latitude::fromNative($value['lat']);
        $this->longitude = Longitude::fromNative($value['lng']);
    }
}
