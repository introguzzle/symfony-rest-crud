<?php

namespace App\Other;

use ReflectionClass;

class Reflector
{
    public static function reflect(object $object): string
    {
        $properties = (new ReflectionClass($object))->getProperties();
        $propertiesString = '';

        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $property->getValue($object);
            $propertiesString .= "$name: " . json_encode($value) . ", ";
        }

        return rtrim($propertiesString, ', ');
    }
}