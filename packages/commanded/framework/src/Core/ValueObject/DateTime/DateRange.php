<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\DateTime;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\Util;
use Commanded\Core\ValueObject\ValueObject;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class DateRange extends AbstractValueObject
{
    private DateTimeInterface $from, $to;

    public function __toString(): string
    {
        if ($this->from !== null && $this->to !== null) {
            return \sprintf('%s - %s', $this->from()->__toString(), $this->to()->__toString());
        }

        if ($this->from !== null) {
            return \sprintf('%s - [inf', $this->from()->__toString());
        }

        return \sprintf('inf] - %s', $this->to()->__toString());
    }

    public function withTimezone(string $timezone): self
    {
        return new self([
            'from' => $this->from()->withTimezone($timezone),
            'to' => $this->to()->withTimezone($timezone),
        ]);
    }

    public function from(): DateTimeInterface
    {
        return $this->from;
    }

    public function to(): DateTimeInterface
    {
        return $this->to;
    }

    public function contains(DateTimeInterface $date): bool
    {
        return $date->between($this->from, $this->to);
    }

    public function equals(ValueObject $other): bool
    {
        return Util::classEquals($this, $other) &&
            $this->from()->equals($other->from()) &&
            $this->to()->equals($other->to());
    }

    public function jsonSerialize()
    {
        return [
            'from' => $this->from()->jsonSerialize(),
            'to' => $this->to()->jsonSerialize(),
        ];
    }

    protected function init($value): void
    {
        Assert::isArray($value);
        Assert::keyExists($value, 'from');
        Assert::keyExists($value, 'to');

        Assert::isInstanceOf($value['from'], DateTimeInterface::class);
        Assert::isInstanceOf($value['to'], DateTimeInterface::class);

        Assert::lessThanEq($value['from']->toNative(), $value['to']->toNative());

        $this->from = $value['from'];
        $this->to = $value['to'];
    }
}
