<?php

namespace Plugo\Services\Mapper;

use ReflectionClass;
use ReflectionException;

class Mapper
{
    /**
     * @param array $array
     * @param string $class
     * @return object
     * @throws ReflectionException
     */
    public function arrayToObject(array $array, string $class): object
    {
        $object = new $class();
        $reflection = new ReflectionClass($object);

        foreach ($array as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                $property->setValue($object, $value);
            }
        }

        return $object;
    }
}
