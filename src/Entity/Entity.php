<?php

namespace App\Entity;

use App\Other\Converter;
use App\Other\Reflector;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use ReflectionClass;
use Stringable;

abstract class Entity implements Stringable, JsonSerializable
{
    /**
     * @var Converter<Entity, array>
     */
    public static Converter $converter;
    /**
     * @param Converter<Entity, array> $converter
     * @return void
     */
    public static function setConverter(Converter $converter): void
    {
        static::$converter = $converter;
    }

    public function convert(?Converter $converter = null): array
    {
        if ($converter !== null) {
            return $converter->convert($this);
        }

        return static::$converter->convert($this);
    }

    /**
     * @param Entity[] $collection
     * @param Converter|null $converter
     * @return array
     */
    public static function convertCollection(
        array|Collection $collection,
        ?Converter       $converter = null
    ): array
    {
        $data = [];

        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        foreach ($collection as $entity) {
            $data[] = $entity->convert($converter);
        }

        return $data;
    }

    public function __toString(): string
    {
        return Reflector::reflect($this);
    }

    public function jsonSerialize(): array
    {
        return $this->convert();
    }

    abstract public function getHiddenProperties(): array;

    public function getClass(): ReflectionClass
    {
        return new ReflectionClass($this);
    }
}