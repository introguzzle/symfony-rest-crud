<?php

namespace App\Other;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template T
 */
abstract class ArrayList implements Countable, IteratorAggregate
{
    /**
     * @return T[]
     */
    abstract public function all(): array;
    public function size(): int
    {
        return count($this->all());
    }

    public function count(): int
    {
        return $this->size();
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->all());
    }
}