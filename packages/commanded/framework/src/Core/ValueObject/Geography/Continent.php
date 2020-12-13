<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Geography;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static Continent AFRICA()
 * @method static Continent EUROPE()
 * @method static Continent ASIA()
 * @method static Continent AMERICA()
 * @method static Continent ANTARCTICA()
 * @method static Continent OCEANIA()
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
class Continent extends Enum
{
    private const AFRICA = 'Africa';
    private const EUROPE = 'Europe';
    private const ASIA = 'Asia';
    private const AMERICA = 'America';
    private const ANTARCTICA = 'Antarctica';
    private const OCEANIA = 'Oceania';

    public function __construct($value)
    {
        Assert::string($value);

        parent::__construct(ucfirst(strtolower($value)));
    }
}
