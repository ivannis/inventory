<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Immutable;

use ArrayAccess;
use Countable;
use Commanded\Core\ValueObject\Util;
use Commanded\Core\ValueObject\ValueObject;
use Iterator;
use SplFixedArray;

abstract class ImmutableArray extends SplFixedArray implements Countable, Iterator, ArrayAccess, ValueObject
{
    public function __construct(array $items)
    {
        parent::__construct(count($items));

        $i = 0;
        foreach($items as $item) {
            $this->guardType($item);
            parent::offsetSet($i++, $item);
        }
    }

    final public function count()
    {
        return parent::count();
    }

    final public function current()
    {
        return parent::current();
    }

    final public function key()
    {
        return parent::key();
    }

    final public function next()
    {
        parent::next();
    }

    final public function rewind()
    {
        parent::rewind();
    }

    final public function valid()
    {
        return parent::valid();
    }

    final public function offsetExists($offset)
    {
        return parent::offsetExists($offset);
    }

    final public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }

    final public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(
            \sprintf('Array %s is immutable', static::class)
        );
    }

    final public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(
            \sprintf('Array %s is immutable', static::class)
        );
    }

    final public function setSize($size)
    {
        throw new \BadMethodCallException(
            \sprintf('Array %s is immutable', static::class)
        );
    }

    public static function fromNative($value): ImmutableArray
    {
        return new static($value);
    }

    public function toNative()
    {
        return $this->toArray();
    }

    public function equals(ValueObject $other): bool
    {
        return Util::classEquals($this, $other) && $this->toNative() === $other->toNative();
    }

    public function jsonSerialize()
    {
        return $this->toNative();
    }

    public function __toString(): string
    {
        return '';
    }
    
    abstract protected function guardType($item): void;
}
