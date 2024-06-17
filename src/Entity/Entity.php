<?php

namespace App\Entity;

use App\Other\Reflector;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use Stringable;

abstract class Entity implements Stringable, JsonSerializable
{
    abstract public function hiddenProperties(): array;
    public function toArray(): array
    {
        $array = [];
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            if (in_array($name = $property->getName(), $this->hiddenProperties(), true)) {
                continue;
            }

            $array[$name] = $this->getPropertyValue($property);
        }

        return $array;
    }

    /**
     * @param Entity[] $array
     * @return array
     */
    public static function fromArray(array|Collection $array): array
    {
        $data = [];

        if ($array instanceof Collection) {
            $array = $array->toArray();
        }

        foreach ($array as $entity) {
            $data[] = $entity->toArray();
        }

        return $data;
    }

    public function __toString(): string
    {
        return Reflector::reflect($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function getPropertyValue(ReflectionProperty $property)
    {
        return $property->getValue($this);
    }
}