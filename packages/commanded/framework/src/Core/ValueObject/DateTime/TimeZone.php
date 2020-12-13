<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use DateTimeZone;
use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\Geography\Coordinate;

/**
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
class TimeZone extends AbstractValueObject
{
    private TimeZoneName $name;
    private Coordinate $location;
    private string $comments;

    public static function fromDefault(): self
    {
        return new static(date_default_timezone_get());
    }

    public function name(): TimeZoneName
    {
        return $this->name;
    }

    public function location(): Coordinate
    {
        return $this->location;
    }

    public function comments(): string
    {
        return $this->comments;
    }

    protected function init($value): void
    {
        Assert::timezone($value);

        $timezone = new DateTimeZone($value);
        $location = $timezone->getLocation();

        $this->name = TimeZoneName::fromNative($value);
        $this->location = Coordinate::fromNative([
            'lat' => $location['latitude'],
            'lng' => $location['longitude'],
        ]);

        $this->comments = $location['comments'];
    }
}
