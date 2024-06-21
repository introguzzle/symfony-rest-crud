<?php

namespace App\Other;

use App\Entity\Entity;

/**
 * @extends Converter<Entity, array>
 */
class EntityConverter implements Converter
{
    /**
     * @param Entity $value
     * @return array
     */
    public function convert($value): array
    {
        $array = [];

        foreach ($value->getClass()->getProperties() as $property) {
            if (in_array($name = $property->getName(), $value->getHiddenProperties(), true)) {
                continue;
            }

            $array[$name] = $property->getValue($value);
        }

        return $array;
    }
}