<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\Number\Real;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class Distance extends AbstractValueObject
{
    const EARTH_RADIUS = 6378136.0; //m

    private Coordinate $from, $to;
    private DistanceUnit $unit;

    public function __toString(): string
    {
        return \sprintf('%F %s', $this->spherical()->toNative(), $this->unit()->toNative());
    }

    public function from(): Coordinate
    {
        return $this->from;
    }

    public function to(): Longitude
    {
        return $this->to;
    }

    public function unit(): DistanceUnit
    {
        return $this->unit;
    }

    /**
     * Returns the approximate flat distance between two coordinates
     * using Pythagoras’ theorem which is not very accurate.
     * @see http://en.wikipedia.org/wiki/Pythagorean_theorem
     * @see http://en.wikipedia.org/wiki/Equirectangular_projection
     */
    public function flat(): Real
    {
        $latA = \deg2rad($this->from->latitude()->toNative());
        $lngA = \deg2rad($this->from->longitude()->toNative());
        $latB = \deg2rad($this->to->latitude()->toNative());
        $lngB = \deg2rad($this->to->longitude()->toNative());
        $x = ($lngB - $lngA) * cos(($latA + $latB) / 2);
        $y = $latB - $latA;

        return $this->convertToUnit(\sqrt(($x * $x) + ($y * $y)) * self::EARTH_RADIUS);
    }

    /**
     * Returns the approximate distance between two coordinates using the spherical trigonometry.
     * @see http://en.wikipedia.org/wiki/Cosine_law
     */
    public function spherical(): Real
    {
        $latA = \deg2rad($this->from->latitude()->toNative());
        $lngA = \deg2rad($this->from->longitude()->toNative());
        $latB = \deg2rad($this->to->latitude()->toNative());
        $lngB = \deg2rad($this->to->longitude()->toNative());
        $degrees = \acos(\sin($latA) * \sin($latB) + \cos($latA) * \cos($latB) * \cos($lngB - $lngA));

        return $this->convertToUnit($degrees * self::EARTH_RADIUS);
    }

    /**
     * Returns the approximate sea level great circle (Earth) distance between
     * two coordinates using the Haversine formula which is accurate to around 0.3%.
     * @see http://www.movable-type.co.uk/scripts/latlong.html
     */
    public function haversine(): Real
    {
        $latA = \deg2rad($this->from->latitude()->toNative());
        $lngA = \deg2rad($this->from->longitude()->toNative());
        $latB = \deg2rad($this->to->latitude()->toNative());
        $lngB = \deg2rad($this->to->longitude()->toNative());
        $dLat = $latB - $latA;
        $dLon = $lngB - $lngA;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($latA) * cos($latB) * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $this->convertToUnit($c * self::EARTH_RADIUS);
    }

    protected function init($value): void
    {
        Assert::isArray($value);
        Assert::keyExists($value, 'from');
        Assert::keyExists($value, 'to');

        $this->from = Coordinate::fromNative($value['from']);
        $this->to = Coordinate::fromNative($value['to']);
        $this->unit = isset($value['unit']) ? DistanceUnit::fromNative($value['unit']) : DistanceUnit::METER();
    }

    /**
     * Converts results in meters to selected unit.
     * The default returned value is in meters.
     */
    private function convertToUnit(float $meters): Real
    {
        switch ($this->unit) {
            case DistanceUnit::METER():
                return $meters;
            case DistanceUnit::KILOMETER():
                return $meters / 1000;
            case DistanceUnit::MILE():
                return $meters / 1609.344;
            case DistanceUnit::FOOT():
                return $meters / 0.3048;
            default:
                return $meters;
        }
    }
}
