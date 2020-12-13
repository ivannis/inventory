<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Enum;

trait EnumTrait
{
    protected static array $cache = [], $defaultCache = [];

    /**
     * @param string $name
     * @param array $arguments
     *
     * @throws \BadMethodCallException
     * @return static
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (\strtoupper($name) === '__DEFAULT' && isset(static::$defaultCache[static::class])) {
            return new static(static::$defaultCache[static::class]);
        }

        if (isset($array[$name])) {
            return new static($array[$name]);
        }

        throw new \BadMethodCallException(
            \sprintf('No static method or enum constant %S in class %s', $name, static::class)
        );
    }

    public static function toArray(): array
    {
        if (! isset(static::$cache[static::class])) {
            static::$cache[static::class] = self::constants(static::class);
            if (! isset(static::$defaultCache[static::class]) && ! empty(static::$cache[static::class])) {
                static::$defaultCache[static::class] = \array_values(static::$cache[static::class])[0];
            }
        }

        return static::$cache[static::class];
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected static function search($value)
    {
        return \array_search($value, static::toArray(), true);
    }

    private static function constants(string $class): array
    {
        $reflection = new \ReflectionClass($class);
        $constants  = [];

        do {
            foreach ($reflection->getConstants() as $name => $value) {
                if (\strtoupper($name) === '__DEFAULT') {
                    static::$defaultCache[$class] = $value;
                } else {
                    $constants[$name] = $value;
                }
            }
        } while (($reflection = $reflection->getParentClass()) && $reflection->name !== __CLASS__);

        return $constants;
    }
}
