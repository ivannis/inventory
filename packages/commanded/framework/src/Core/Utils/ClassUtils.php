<?php

declare(strict_types=1);

namespace Commanded\Core\Utils;

final class ClassUtils
{
    /**
     * Fully qualified class name of an object, without a leading backslash
     * @param object|string $object
     */
    static function fqcn($object): string
    {
        if (is_string($object)) {
            return str_replace('.', '\\', $object);
        }

        if (is_object($object)) {
            return trim(get_class($object), '\\');
        }

        throw new \InvalidArgumentException(sprintf("Expected an object or a string, got %s", gettype($object)));
    }

    /**
     * Canonical class name of an object, of the form "My.Namespace.MyClass"
     * @param object|string $object
     */
    static function canonical($object): string
    {
        return str_replace('\\', '.', static::fqcn($object));
    }

    /**
     * Underscored and lowercased class name of an object, of the form "my.mamespace.my_class"
     * @param object|string $object
     */
    static function underscore($object, string $separator = '_'): string
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', $separator.'$1', static::canonical($object)));
    }

    /**
     * The class name of an object, without the namespace
     * @param object|string $object
     */
    static function short($object): string
    {
        $parts = explode('\\', static::fqcn($object));
        return end($parts);
    }

    /**
     * Returns an array of CONSTANT_NAME => contents for a given class
     * @param string $className
     * @return string[]
     */
    static function constants($className)
    {
        return (new \ReflectionClass($className))->getConstants();
    }

    /**
     * The message name of an object, without the namespace
     * @param object|string $object
     */
    static function messageName($object): string
    {
        return static::underscore(static::short($object), '-');
    }
}