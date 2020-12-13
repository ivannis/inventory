<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Enum;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\ValueObject;

/**
 * Enum class based on http://github.com/myclabs/php-enum.
 *
 * @method static static __DEFAULT()
 */
class Enum extends AbstractValueObject
{
    use EnumTrait;

    public function name(): string
    {
        return static::search($this->toNative());
    }

    public static function names(): array
    {
        return \array_keys(static::toArray());
    }

    /**
     * @return static[]
     */
    public static function values()
    {
        $values = [];
        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
    }

    public function is($value): bool
    {
        return $this->toNative() === $value;
    }

    /**
     * @return static
     */
    public static function ensure(Enum $enum = null, Enum $default = null)
    {
        if ($enum instanceof static) {
            return $enum;
        }

        if ($enum === null) {
            return $default === null ? static::__DEFAULT() : static::ensure($default);
        }

        throw new \InvalidArgumentException(
            \sprintf('The enum parameter must be a %s instance', static::class)
        );
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof static && $this->is($other->toNative());
    }

    protected function init($value): void
    {
        Assert::oneOf($value, static::toArray());
    }
}
