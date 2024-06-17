<?php

namespace App\Other;

use ReflectionClass;
use Throwable;

class Reflector
{
    public static function reflect(object $object): string
    {
        $properties = (new ReflectionClass($object))->getProperties();
        $propertiesString = '';

        foreach ($properties as $property) {
            $name = $property->getName();

            try {
                $value = $property->getValue($object);
            } catch (Throwable) {
                $value = null;
            }

            $propertiesString .= "$name: " . json_encode($value) . ", ";
        }

        return rtrim($propertiesString, ', ');
    }
}